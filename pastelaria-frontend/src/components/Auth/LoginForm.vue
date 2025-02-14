<template>
  <q-form @submit.prevent="onSubmitLogin" class="q-gutter-y-lg">
    <q-input v-model="email" dense label="Email" hint="Insira seu e-mail." type="email" lazy-rules
      :rules="[(val: string) => !!val || 'Por favor informe o seu e-mail']">
      <template v-slot:prepend>
        <q-icon name="mail" />
      </template>
    </q-input>

    <q-input v-model="password" dense :type="isPwd ? 'password' : 'text'" label="Senha" hint="Insira sua senha."
      lazy-rules :rules="[(val: string) => !!val || 'Por favor informe a sua senha']">
      <template v-slot:prepend>
        <q-icon name="key" />
      </template>
      <template v-slot:append>
        <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer" @click="isPwd = !isPwd" />
      </template>
    </q-input>

    <q-btn label="Login" type="submit" size="md" color="primary" class="full-width" dense :loading="loading"
      :disable="loading" />
  </q-form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from 'src/stores/auth-store'
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const isPwd = ref(true)
const loading = ref(false)
const $q = useQuasar()
const authStore = useAuthStore()
const router = useRouter();

const onSubmitLogin = async () => {
  if (!email.value || !password.value) {
    $q.notify({
      type: 'warning',
      message: 'Por favor, preencha todos os campos.',
      position: 'bottom',
    })
    return
  }

  loading.value = true

  try {
    const { success, message } = await authStore.login(email.value, password.value)

    if (success) {
      $q.notify({
        type: 'positive',
        message: 'Login realizado com sucesso!',
        position: 'bottom',
      })
      // Redireciona após login bem-sucedido
      await router.push('/pastelaria')
    } else {
      $q.notify({
        type: 'negative',
        message: message || 'Erro ao realizar login.',
        position: 'bottom',
      })
    }
  } catch (error) {
    console.error('Erro ao tentar logar:', error)
    $q.notify({
      type: 'negative',
      message: 'Ocorreu um erro inesperado.',
      position: 'bottom',
    })
  } finally {
    loading.value = false
  }
}
</script>
