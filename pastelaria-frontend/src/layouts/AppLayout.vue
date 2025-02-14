<template>
  <q-layout view="hHh lpR lfr">

    <AppHeader @toggleLeftDrawer="toggleLeftDrawer" :isLoggingOut="isLoggingOut" @onClickLogout="onClickLogout" />

    <AppDrawer v-model="leftDrawerOpen" />

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from 'src/stores/auth-store';
import AppHeader from 'src/components/AppHeader.vue';
import AppDrawer from 'src/components/AppDrawer.vue';
import { showSuccessNotification, showErrorNotification } from 'src/services/notificationService';

const authStore = useAuthStore();
const router = useRouter();
const leftDrawerOpen = ref(false);
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
  leftDrawerOpen.value = !leftDrawerOpen.value;
};
</script>
