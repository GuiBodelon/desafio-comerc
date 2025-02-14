<template>
  <q-page>
    <div class="row">
      <div class="col-12">
        <q-banner class="bg-primary text-white">
          <div class="text-h6">Escolha seus produtos</div>
          <div class="text-body2">Adicione os itens ao seu carrinho e finalize o pedido.</div>
        </q-banner>
      </div>
      <!-- Lista de Produtos -->
      <div class="col-7 flex justify-center items-center" v-if="isLoadingProducts" style="min-height: 80vh;">
        <q-spinner-oval color="primary" size="10em" />
      </div>
      <div v-else class="col-7 products q-pa-md">
        <q-card class="q-mt-md " v-for="product in products" :key="product.id" flat bordered>
          <q-card-section>
            <div class="row items-center">
              <div class="col-2">
                <img :src="`http://127.0.0.1:8080/storage/${product.photo}`" alt="Product" class="q-mb-md"
                  style="width: 100%; height: auto;">
              </div>
              <div class="col-10 q-pl-md">
                <div class="text-h6">{{ product.name }}</div>
                <div class="text-body2">R$ {{ Number(product.price).toFixed(2) }}</div>
                <q-btn @click="addToCart(product)" color="primary" label="Adicionar ao carrinho" class="q-mt-md" />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <!-- Carrinho -->
      <div class="col-5 q-pa-md">
        <q-card class="q-mt-md" flat bordered>
          <q-card-section>
            <div class="text-h6">Carrinho</div>
            <q-separator />
            <div v-for="(item, index) in cart" :key="index" class="q-my-sm row items-center">
              <div class="col-1 text-center">
                <span class="q-mx-sm">{{ item.quantity }}x</span>
              </div>
              <div class="col-6">
                <div>{{ item.name }}</div>
              </div>
              <div class="col-2">
                <div>R$ {{ (item.price * item.quantity).toFixed(2) }}</div>
              </div>
              <div class="col-2 flex justify-end">
                <q-btn class="q-mr-sm" icon="remove" color="negative" size="sm" @click="removeFromCart(index)" />
                <q-btn icon="add" color="positive" size="sm" @click="increaseQuantity(index)" />
              </div>
            </div>
            <q-separator />
            <div class="row items-center">
              <div class="col-8">
                <div>Total:</div>
              </div>
              <div class="col-4">
                <div class="text-h6">R$ {{ total.toFixed(2) }}</div>
              </div>
            </div>
            <q-btn label="Finalizar Pedido" color="primary" @click="askIfExistingCustomer" class="full-width q-mt-md" />
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Confirmação se já é cliente -->
    <q-dialog v-model="showCustomerCheckDialog">
      <q-card style="width: 350px">
        <q-card-section class="text-center">
          <div class="text-h6">Já é cliente?</div>
          <div v-if="showEmailForm" class="text-subtitle">
            Informe seu e-mail
          </div>
          <q-btn v-if="!showEmailForm" flat @click="newCustomer" label="Não" color="negative" class="q-mt-md" />
          <q-btn v-if="!showEmailForm" flat @click="existingCustomer" label="Sim" color="primary" class="q-mt-md" />
          <q-input class="q-mt-md" v-if="showEmailForm" v-model="clientEmail" dense label="Email"
            hint="Insira seu e-mail." type="email" lazy-rules
            :rules="[(val: string) => !!val || 'Por favor informe o seu e-mail']">
            <template v-slot:prepend>
              <q-icon name="mail" />
            </template>
          </q-input>
        </q-card-section>
        <q-card-actions v-if="showEmailForm">
          <q-btn flat label="Cancelar" color="red-6" @click="closeDialog" />
          <q-btn flat label="Buscar" color="primary" @click="findCustomerByEmail" :loading="isLoading"
            :disable="isLoading" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Formulário de Cadastro de Cliente -->
    <q-dialog v-model="showClientForm" persistent>
      <q-card style="width:400px">
        <q-card-section>
          <div class="text-h6">Cadastro</div>
          <q-form @submit.prevent="handleSubmit">
            <q-input v-model="clientData.name" label="Nome" required />
            <q-input v-model="clientData.email" label="Email" type="email" required />
            <q-input v-model="clientData.phone" label="Telefone" required />
            <q-input v-model="clientData.birth_date" label="Data de Nascimento" type="date" required />
            <q-input v-model="clientData.address" label="Endereço" required />
            <q-input v-model="clientData.complement" label="Complemento" />
            <q-input v-model="clientData.neighborhood" label="Bairro" required />
            <q-input v-model="clientData.zip_code" label="CEP" required />
            <q-card-actions class="q-px-none">
              <q-btn flat label="Cancelar" color="red-6" @click="closeDialog" />
              <q-btn flat label="Salvar" type="submit" color="primary" :loading="loadingSubmit" v-close-popup />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { orderService } from 'src/services/orderService';
import type { Product, Customer } from 'src/components/models';
import { showSuccessNotification, showErrorNotification, notificationHourglass, removeNotification } from 'src/services/notificationService';
import { Notify } from 'quasar';

// Reatividade
const products = ref<Product[]>([]);
const cart = ref<{ id: number; name: string; price: number; quantity: number }[]>([]);
const showClientForm = ref(false);
const showCustomerCheckDialog = ref(false);
const showEmailForm = ref(false);
const isLoadingProducts = ref(false);
const isLoading = ref(false);
const clientEmail = ref('');
const clientData = ref({
  name: '',
  email: '',
  phone: '',
  birth_date: '',
  address: '',
  complement: '',
  neighborhood: '',
  zip_code: ''
});
const loadingSubmit = ref(false);
// Funções
const getProducts = async () => {
  isLoadingProducts.value = true
  try {
    products.value = await orderService.fetchProducts(); // Chama o serviço para pegar os produtos
  } catch (error) {
    console.error('Erro ao carregar produtos:', error);
    showErrorNotification('Erro ao carregar produtos');
  } finally {
    isLoadingProducts.value = false
  }
};

const askIfExistingCustomer = () => {
  showCustomerCheckDialog.value = true;
};

const existingCustomer = () => {
  showEmailForm.value = true;
};

const newCustomer = () => {
  showCustomerCheckDialog.value = false;
  showClientForm.value = true;
};

const closeDialog = () => {
  showClientForm.value = false;
  showEmailForm.value = false;
};

// Funções do carrinho
const total = computed(() => {
  return cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0);
});

const addToCart = (product: Product) => {
  const itemInCart = cart.value.find(item => item.id === product.id);
  if (itemInCart) {
    itemInCart.quantity++;
  } else {
    cart.value.push({ ...product, quantity: 1 });
  }
};

const removeFromCart = (index: number) => {
  cart.value.splice(index, 1);
};

const increaseQuantity = (index: number) => {
  const item = cart.value[index];
  if (item) { // Verifica se o item realmente existe
    item.quantity++;
  }
};

// Funções de cliente
const findCustomerByEmail = async () => {
  try {
    // Exibe o loader
    const notification = notificationHourglass('Buscando informações...');
    isLoading.value = true;
    const response = await orderService.findCustomer(clientEmail.value);
    isLoading.value = false;
    removeNotification(notification); // Remove a notificação de carregamento
    if (response) {
      showCustomerCheckDialog.value = false
      // Exibe a notificação com opções de realizar o pedido ou não
      Notify.create({
        message: 'E-mail encontrado. Deseja realizar o pedido?',
        color: 'dark',
        position: 'top',
        timeout: 0,
        actions: [
          {
            icon: 'close',
            color: 'red-6',
            label: 'Não',
            handler: () => {
              console.log('Pedido cancelado');
            }
          },
          {
            icon: 'check',
            color: 'primary',
            label: 'Sim, realizar pedido',
            handler: () => {
              const handleSubmitOrder = async () => {
                await submitOrder(response);
              };
              void handleSubmitOrder();
            }
          }
        ]
      });
    } else {
      showErrorNotification('Cliente não encontrado!');
    }
  } catch (error) {
    isLoading.value = false;
    showErrorNotification('Erro ao buscar cliente');
    console.error(error);
  }
};

const submitOrder = async (customerData: Customer) => {
  try {
    const notification = notificationHourglass('Finalizando o pedido...');

    // Monta o pedido com os IDs dos produtos e suas respectivas quantidades
    const orderData = {
      customer_id: customerData.id,
      products: cart.value.map(item => ({
        product_id: item.id,
        quantity: item.quantity
      }))
    };

    await orderService.submitOrder(orderData);
    removeNotification(notification);
    showSuccessNotification('Pedido realizado com sucesso!');
  } catch (error) {
    showErrorNotification('Erro ao enviar pedido');
    console.error(error);
  }
};


const handleSubmit = async () => {
  loadingSubmit.value = true;
  try {
    const notification = notificationHourglass('Cadastrando cliente...');
    await orderService.createCustomer(clientData.value);
    showSuccessNotification('Cliente cadastrado com sucesso!');
    removeNotification(notification);
    showEmailForm.value = true;
  } catch (error) {
    showErrorNotification('Erro ao cadastrar cliente');
    console.error(error);
  } finally {
    loadingSubmit.value = false;
  }
};

// Função para fechar o modal
onMounted(async () => {
  await getProducts();
});
</script>
<style lang="scss" scoped>
.products {
  max-height: 75vh;
  overflow-y: scroll;
}
</style>
