<template>
  <q-page>
    <q-table title="Clientes" :rows="clients" :columns="columns" row-key="id" :loading="loadingTable"
      :filter="filterClientsTable">
      <template v-slot:loading>
        <q-inner-loading showing color="primary" />
      </template>

      <template v-slot:top="props">
        <q-btn icon="refresh" @click="loadClients" color="primary" class="q-mr-md">
          <q-tooltip>Atualizar Tabela</q-tooltip>
        </q-btn>
        <q-btn @click="openCreateDialog" label="Adicionar Cliente" color="primary" />
        <q-space />
        <q-input dense debounce="300" v-model="filterClientsTable" placeholder="Procurar" class="q-mr-md">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
        <q-btn flat dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" @click="props.toggleFullscreen"
          size="lg" color="primary">
          <q-tooltip class="text-caption">FullScreen</q-tooltip>
        </q-btn>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td key="actions" :props="props" class="q-gutter-x-sm">
          <q-btn color="primary" icon="edit" @click="openEditDialog(props.row)" dense />
          <q-btn color="negative" icon="delete" @click="clientToDelete = props.row; confirmationDialogOpen = true"
            dense />
        </q-td>
      </template>
    </q-table>

    <!-- Modal de Criação/Atualização de Cliente -->
    <q-dialog v-model="dialogOpen" persistent>
      <q-card>
        <q-card-section>
          <div class="text-h6">{{ isEditing ? 'Editar Cliente' : 'Criar Cliente' }}</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit.prevent="handleSubmit">
            <q-input v-model="clientData.name" label="Nome" required />
            <q-input v-model="clientData.email" label="Email" type="email" required />
            <q-input v-model="clientData.phone" label="Telefone" required />
            <q-input v-model="clientData.birth_date" label="Data de Nascimento" type="date" :error="birthDateError"
              @blur="validateBirthDate" required />
            <div v-if="birthDateError" class="text-negative text-caption">{{ birthDateError }}</div>
            <q-input v-model="clientData.address" label="Endereço" required />
            <q-input v-model="clientData.complement" label="Complemento" />
            <q-input v-model="clientData.neighborhood" label="Bairro" required />
            <q-input v-model="clientData.zip_code" label="CEP" required />
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
            Você tem certeza que deseja excluir este cliente?
          </div>
        </q-card-section>
        <q-card-actions>
          <q-btn flat label="Cancelar" color="negative" @click="confirmationDialogOpen = false" />
          <q-btn flat label="Excluir" color="primary" @click="confirmDeleteClient" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script lang="ts" setup>
import { ref, reactive, onMounted } from 'vue';
import { fetchClients, createClient, updateClient, deleteClient as deleteClientService } from 'src/services/clientsService';
import { showSuccessNotification, showErrorNotification, notificationHourglass, removeNotification } from 'src/services/notificationService';
import type { QTableProps } from 'quasar';
import type { Customer } from 'src/components/models';

// Estado do componente
const clients = ref<Customer[]>([]);
const confirmationDialogOpen = ref(false);
const clientToDelete = ref<Customer | null>(null);
const filterClientsTable = ref('');
const loadingTable = ref(false);
const loadingSubmit = ref(false);
const dialogOpen = ref(false);
const isEditing = ref(false);
const clientData = reactive({
  id: 0,
  name: '',
  email: '',
  phone: '',
  birth_date: '',
  address: '',
  complement: '',
  neighborhood: '',
  zip_code: ''
});
const birthDateError = ref<boolean | null>(null); // Definido como boolean ou null

// Definição das colunas da tabela
const columns: QTableProps['columns'] = [
  { name: 'name', label: 'Nome', required: true, align: 'left', field: row => row.name },
  { name: 'email', label: 'Email', align: 'left', field: row => row.email },
  { name: 'phone', label: 'Telefone', align: 'left', field: row => row.phone },
  { name: 'birth_date', label: 'Data de Nascimento', align: 'left', field: row => row.birth_date },
  { name: 'address', label: 'Endereço', align: 'left', field: row => row.address },
  { name: 'complement', label: 'Complemento', align: 'left', field: row => row.complement },
  { name: 'neighborhood', label: 'Bairro', align: 'left', field: row => row.neighborhood },
  { name: 'zip_code', label: 'CEP', align: 'left', field: row => row.zip_code },
  { name: 'actions', label: 'Ações', align: 'center', field: () => '' }
];

// Função para abrir o modal de criação
const openCreateDialog = () => {
  clientData.id = 0;
  clientData.name = '';
  clientData.email = '';
  clientData.phone = '';
  clientData.birth_date = '';
  clientData.address = '';
  clientData.complement = '';
  clientData.neighborhood = '';
  clientData.zip_code = '';
  isEditing.value = false;
  dialogOpen.value = true;
};

// Função para abrir o modal de edição
const openEditDialog = (client: Customer) => {
  clientData.id = client.id;
  clientData.name = client.name;
  clientData.email = client.email;
  clientData.phone = client.phone;
  // Converter dd/MM/yyyy para yyyy-MM-dd
  const [day, month, year] = client.birth_date.split('/');
  clientData.birth_date = `${year}-${month}-${day}`;
  clientData.address = client.address;
  clientData.complement = client.complement;
  clientData.neighborhood = client.neighborhood;
  clientData.zip_code = client.zip_code;
  isEditing.value = true;
  dialogOpen.value = true;
};

// Função para validar a data de nascimento
const validateBirthDate = (): boolean => {
  // Verificando se o campo birth_date é uma data válida
  const birthDate = new Date(clientData.birth_date);
  if (isNaN(birthDate.getTime())) {
    return false;
  }

  const minDate = new Date();
  minDate.setFullYear(minDate.getFullYear() - 120);
  const maxDate = new Date();
  maxDate.setFullYear(maxDate.getFullYear() - 10);

  // Verifica se a data está dentro do intervalo de 10 a 120 anos
  if (birthDate < minDate || birthDate > maxDate) {
    return false;
  }

  // Verificar se o ano tem exatamente 4 dígitos
  const year = birthDate.getFullYear();
  if (year.toString().length !== 4) {
    return false;
  }

  return true;
};

// Função para fechar o modal
const closeDialog = () => {
  dialogOpen.value = false;
};

// Função para confirmar a exclusão do cliente
const confirmDeleteClient = async () => {
  confirmationDialogOpen.value = false;
  if (clientToDelete.value) {
    await deleteClient(clientToDelete.value.id);
    clientToDelete.value = null;
  }
};

// Função para buscar a lista de clientes
const loadClients = async () => {
  loadingTable.value = true;
  try {
    clients.value = await fetchClients();
  } catch (error) {
    console.log(error);
    showErrorNotification('Erro ao carregar clientes');
  } finally {
    loadingTable.value = false;
  }
};

// Função para lidar com o envio do formulário
const handleSubmit = async () => {
  // Validar a data de nascimento
  if (!validateBirthDate()) {
    showErrorNotification("Data de nascimento inválida.");
    return;
  }

  loadingSubmit.value = true;

  // Criar a notificação e armazenar a referência
  const notification = notificationHourglass(isEditing.value ? 'Atualizando cliente...' : 'Criando cliente...');

  try {
    if (isEditing.value) {
      await updateClient(clientData.id, clientData);
    } else {
      await createClient(clientData);
    }

    // Remover a notificação persistente
    removeNotification(notification);

    // Exibir a notificação de sucesso
    showSuccessNotification(isEditing.value ? 'Cliente atualizado com sucesso!' : 'Cliente criado com sucesso!');
    await loadClients();
    closeDialog();
  } catch (error) {
    console.log(error);

    // Remover a notificação persistente
    removeNotification(notification);

    // Exibir a notificação de erro
    showErrorNotification(isEditing.value ? 'Erro ao atualizar cliente' : 'Erro ao criar cliente');
  } finally {
    loadingSubmit.value = false;
  }
};

// Função para deletar um cliente
const deleteClient = async (id: number) => {
  const notification = notificationHourglass('Excluindo cliente...');
  try {
    await deleteClientService(id);
    removeNotification(notification);
    showSuccessNotification('Cliente excluído com sucesso!');
    await loadClients();
  } catch (error) {
    console.log(error);
    removeNotification(notification);
    showErrorNotification('Erro ao excluir cliente');
  }
};

// Carregar a lista de clientes ao montar o componente
onMounted(loadClients);
</script>

<style lang="scss" scoped>
.q-card {
  min-width: 450px !important;
}
</style>
