<template>
  <q-page>
    <q-table title="Produtos" :rows="products" :columns="columns" row-key="id" :loading="loadingTable"
      :filter="filterProductsTable">
      <template v-slot:loading>
        <q-inner-loading showing color="primary" />
      </template>

      <template v-slot:top="props">
        <q-btn icon="refresh" @click="loadProducts" color="primary" class="q-mr-md">
          <q-tooltip>Atualizar Tabela</q-tooltip>
        </q-btn>
        <q-btn @click="openCreateDialog" label="Adicionar Produto" color="primary" />
        <q-space />
        <q-input dense debounce="300" v-model="filterProductsTable" placeholder="Procurar" class="q-mr-md">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
        <q-btn flat dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" @click="props.toggleFullscreen"
          size="lg" color="primary">
          <q-tooltip class="text-caption">FullScreen</q-tooltip>
        </q-btn>
      </template>

      <template v-slot:body-cell-photo="props">
        <q-td :props="props">
          <q-img :src="`http://127.0.0.1:8080/storage/${props.row.photo}`" :alt="props.row.name"
            style="max-width: 100px; object-fit: contain;" />
        </q-td>
      </template>


      <template v-slot:body-cell-price="props">
        <q-td :props="props">
          R$ {{ formatPrice(props.row.price) }}
        </q-td>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td key="actions" :props="props" class="q-gutter-x-sm">
          <q-btn color="primary" icon="edit" @click="openEditDialog(props.row)" dense />
          <q-btn color="negative" icon="delete" @click="productToDelete = props.row; confirmationDialogOpen = true"
            dense />
        </q-td>
      </template>
    </q-table>

    <!-- Modal de Criação/Atualização de Produto -->
    <q-dialog v-model="dialogOpen" persistent>
      <q-card>
        <q-card-section>
          <div class="text-h6">{{ isEditing ? 'Editar Produto' : 'Criar Produto' }}</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit.prevent="handleSubmit" class="q-gutter-y-md">
            <q-input v-model="productData.name" label="Nome" required />
            <q-input v-model="productData.price" label="Preço" type="text" required mask="#,##" fill-mask="0"
              reverse-fill-mask />
            <q-file v-model="productData.photo" label="Selecione a Foto" :max-file-size="2 * 1024 * 1024" counter
              accept=".jpg, image/*" @added="onFileAdded" @rejected="onRejected" />

            <q-separator spaced inset dark horizontal />
            <div v-if="photoPath" class="text-h6">Imagem do Produto:</div>
            <div v-if="photoPath" class="flex justify-center">
              <q-img :src="photoPath" :alt="productData.name" style="max-width: 200px; height: auto;"
                class="photoBox" />
            </div>
            <q-card-actions>
              <q-btn flat label="Cancelar" color="red-6" @click="closeDialog" />
              <q-btn flat label="Salvar" type="submit" color="primary" :loading="loadingSubmit" v-close-popup />
            </q-card-actions>

          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <!-- Modal de Confirmação de Exclusão -->
    <q-dialog v-model="confirmationDialogOpen" persistent>
      <q-card class="q-pa-md">
        <q-card-section>
          <div class="text-h6">Confirmar Exclusão</div>
          <div class="q-mt-md">
            Você tem certeza que deseja excluir este produto?
          </div>
        </q-card-section>
        <q-card-actions>
          <q-btn flat label="Cancelar" color="negative" @click="confirmationDialogOpen = false" />
          <q-btn flat label="Excluir" color="primary" @click="confirmDeleteProduct" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script lang="ts" setup>
import { ref, reactive, onMounted } from 'vue';
import { showSuccessNotification, showErrorNotification, notificationHourglass, removeNotification } from 'src/services/notificationService';
import { fetchProducts, createProduct, updateProduct, deleteProduct as deleteProductService } from 'src/services/productsService';
import type { Product } from 'src/components/models';
import type { QRejectedEntry, QTableProps } from 'quasar';
import { Notify } from 'quasar'

// Estado do componente
const products = ref<Product[]>([]);
const confirmationDialogOpen = ref(false);
const productToDelete = ref<Product | null>(null);
const filterProductsTable = ref('');
const loadingTable = ref(false);
const loadingSubmit = ref(false);
const dialogOpen = ref(false);
const isEditing = ref(false);
const photoPath = ref('');

const onFileAdded = (files: File[]) => {
  if (files.length > 0) {
    const file = files[0];
    productData.photo = file || null;
  }
};

const onRejected = (rejectedEntries: QRejectedEntry[]) => {
  const largeFiles = rejectedEntries.filter(entry => entry.file && entry.file.size > 2048 * 1024); // 2MB em bytes
  if (largeFiles.length > 0) {
    Notify.create({
      type: 'negative',
      message: `${largeFiles.length} arquivo(s) muito grande(s), máximo permitido: 2MB`
    });
  }
};

const productData = reactive({
  id: 0,
  name: '',
  price: 0,
  photo: null as File | null
});

// Definição das colunas da tabela
const columns: QTableProps['columns'] = [
  { name: 'name', label: 'Nome', required: true, align: 'left', field: row => row.name },
  { name: 'price', label: 'Preço', align: 'left', field: row => row.price },
  { name: 'photo', label: 'Foto', align: 'left', field: row => row.photo },
  { name: 'actions', label: 'Ações', align: 'center', field: () => '' }
];

// Função para abrir o modal de criação
const openCreateDialog = () => {
  productData.id = 0;
  productData.name = '';
  productData.price = 0;
  isEditing.value = false;
  dialogOpen.value = true;
};

const openEditDialog = (product: Product) => {
  productData.id = product.id;
  productData.name = product.name;
  productData.price = product.price;
  isEditing.value = true;
  dialogOpen.value = true;
  photoPath.value = product.photo ? `http://127.0.0.1:8080/storage/${product.photo}` : '';
};

// Função para fechar o modal
const closeDialog = () => {
  dialogOpen.value = false;
};

// Função para confirmar a exclusão do produto
const confirmDeleteProduct = async () => {
  confirmationDialogOpen.value = false;
  if (productToDelete.value) {
    await deleteProduct(productToDelete.value.id);
    productToDelete.value = null;
  }
};

// Função para carregar os produtos
const loadProducts = async () => {
  loadingTable.value = true;
  try {
    products.value = await fetchProducts();
  } catch (error) {
    console.log(error)
    showErrorNotification('Erro ao carregar produtos');
  } finally {
    loadingTable.value = false;
  }
};

const formatProductName = (name: string) => {
  return name
    .split(' ')
    .map((word, index) => {
      const lowerCaseWords = ['de', 'da', 'do', 'das', 'dos'];
      if (lowerCaseWords.includes(word.toLowerCase()) && index > 0) {
        return word.toLowerCase(); // Mantém "de", "da", "do", etc. em minúsculas
      }
      return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase(); // Primeira letra maiúscula
    })
    .join(' ');
};

const formatPrice = (price: number) => {
  return price.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
};

const parsePrice = (price: string) => {
  // Remover R$ e qualquer caractere não numérico
  return parseFloat(price.replace('R$', '').replace(/\./g, '').replace(',', '.'));
};

// Função para enviar o formulário
const handleSubmit = async () => {
  loadingSubmit.value = true;

  // Formatando o nome do produto
  productData.name = formatProductName(productData.name)

  // Formatando o preço para envio ao banco (sem formato de moeda)
  const formattedPrice = parsePrice(productData.price.toString());

  // Verificando se os campos estão corretos
  if (!productData.name || !formattedPrice || !productData.photo) {
    showErrorNotification('Por favor, preencha todos os campos obrigatórios.');
    loadingSubmit.value = false;
    return;
  }

  const formData = new FormData();
  formData.append('name', productData.name);
  formData.append('price', formattedPrice.toString());

  if (productData.photo) {
    formData.append('photo', productData.photo);
  }

  // Adicionando o _method apenas se for uma operação de atualização (PUT)
  if (isEditing.value) {
    formData.append('_method', 'PUT');
  }

  try {
    const notification = notificationHourglass(isEditing.value ? 'Atualizando produto...' : 'Criando produto...');

    // Realizando a operação de atualização ou criação
    if (isEditing.value) {
      await updateProduct(productData.id, formData);
    } else {
      await createProduct(formData);
    }

    removeNotification(notification);
    showSuccessNotification(isEditing.value ? 'Produto atualizado com sucesso!' : 'Produto criado com sucesso!');

    // Recarregar lista de produtos ou fechar o formulário
    await loadProducts(); // Certifique-se de implementar a função loadProducts
    closeDialog(); // Fechar o modal ou formulário
  } catch (error) {
    console.log(error);
    showErrorNotification(isEditing.value ? 'Erro ao atualizar produto' : 'Erro ao criar produto');
  } finally {
    loadingSubmit.value = false;
  }
};

// Função para deletar um produto
const deleteProduct = async (id: number) => {
  const notification = notificationHourglass('Excluindo produto...');
  try {
    await deleteProductService(id);
    removeNotification(notification);
    showSuccessNotification('Produto excluído com sucesso!');
    await loadProducts();
  } catch (error) {
    console.log(error)
    removeNotification(notification);
    showErrorNotification('Erro ao excluir produto');
  }
};

// Carregar a lista de produtos ao montar o componente
onMounted(loadProducts);
</script>

<style lang="scss" scoped>
.q-card {
  min-width: 450px !important;
}

.photoBox {
  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
  border-radius: 10px;
}
</style>
