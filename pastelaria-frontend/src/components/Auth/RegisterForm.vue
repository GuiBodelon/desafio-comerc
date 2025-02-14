<template>
  <q-form @submit.prevent="onSubmitRegister" class="q-gutter-y-lg">
    <q-input v-model="name" dense label="Nome" hint="Digite seu nome completo" type="text" lazy-rules
      :rules="[(val) => !!val || 'O nome é obrigatório']">
      <template v-slot:prepend>
        <q-icon name="person" />
      </template>
    </q-input>

    <q-input v-model="email" dense label="Email" hint="Insira seu e-mail." type="email" lazy-rules
      :rules="[(val) => !!val || 'O e-mail é obrigatório']">
      <template v-slot:prepend>
        <q-icon name="mail" />
      </template>
    </q-input>

    <q-input v-model="password" dense :type="isPwd ? 'password' : 'text'" label="Senha" hint="Mínimo 6 caracteres."
      lazy-rules :rules="[(val) => !!val || 'A senha é obrigatória']">
      <template v-slot:prepend>
        <q-icon name="key" />
      </template>
      <template v-slot:append>
        <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer" @click="isPwd = !isPwd" />
      </template>
    </q-input>

    <q-input v-model="password_confirmation" dense :type="isPwd ? 'password' : 'text'" label="Confirme sua senha"
      hint="Digite a senha novamente." lazy-rules :rules="[(val) => val === password || 'As senhas não coincidem']">
      <template v-slot:prepend>
        <q-icon name="key" />
      </template>
    </q-input>

    <q-btn label="Registrar" type="submit" size="md" color="primary" class="full-width" dense :loading="loading"
      :disable="loading" />
  </q-form>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from 'src/stores/auth-store';
import { useQuasar } from 'quasar';
import { useRouter } from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const isPwd = ref(true);
const loading = ref(false);
const $q = useQuasar();
const router = useRouter();
const authStore = useAuthStore();

const onSubmitRegister = async () => {
  if (!name.value || !email.value || !password.value || !password_confirmation.value) {
    $q.notify({
      type: 'warning',
      message: 'Por favor, preencha todos os campos.',
      position: 'bottom',
    });
    return;
  }

  if (password.value !== password_confirmation.value) {
    $q.notify({
      type: 'warning',
      message: 'As senhas não coincidem.',
      position: 'bottom',
    });
    return;
  }

  loading.value = true;

  try {
    const { success, message } = await authStore.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });

    if (success) {
      $q.notify({
        type: 'positive',
        message: 'Registro realizado com sucesso!',
        position: 'bottom',
      });

      await router.push('/pastelaria');
    } else {
      $q.notify({
        type: 'negative',
        message: message || 'Erro ao realizar registro.',
        position: 'bottom',
      });
    }
  } catch (error) {
    console.error('Erro ao tentar registrar:', error);
    $q.notify({
      type: 'negative',
      message: 'Ocorreu um erro inesperado.',
      position: 'bottom',
    });
  } finally {
    loading.value = false;
  }
};
</script>
