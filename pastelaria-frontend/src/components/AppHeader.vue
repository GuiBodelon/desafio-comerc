<template>
  <q-header reveal elevated class="bg-dark text-white q-pa-md">
    <q-toolbar>
      <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
      <q-toolbar-title>
        Pastelaria
      </q-toolbar-title>
      <LogoutButton :isLoggingOut="isLoggingOut" @onClickLogout="onClickLogout" />
    </q-toolbar>
  </q-header>
</template>

<script setup lang="ts">
import { ref, defineEmits } from 'vue';
import LogoutButton from 'src/components/Auth/LogoutButton.vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from 'src/stores/auth-store';
import { showSuccessNotification, showErrorNotification } from 'src/services/notificationService';

// Definindo a emissão de eventos
const emit = defineEmits<{
  (e: 'toggleLeftDrawer'): void;
}>();

const authStore = useAuthStore();
const router = useRouter();
const isLoggingOut = ref(false);

const onClickLogout = async () => {
  if (isLoggingOut.value) return;

  isLoggingOut.value = true;

  try {
    await authStore.logout();
    showSuccessNotification('Você saiu da sua conta.');

    await router.push('/');
  } catch (error) {
    console.error('Erro ao tentar deslogar:', error);
    showErrorNotification('Erro ao sair. Tente novamente.');
  } finally {
    isLoggingOut.value = false;
  }
};

const toggleLeftDrawer = () => {
  emit('toggleLeftDrawer');
};
</script>
