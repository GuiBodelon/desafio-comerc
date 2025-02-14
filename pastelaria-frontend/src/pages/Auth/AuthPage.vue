<template>
  <q-page class="bg-dark row justify-center items-center">
    <div class="authBox shadow-3 bg-white text-center">
      <div class="col-12 flex justify-center q-pa-xl bg-dark">
        <q-img src="logo.webp" class="logo" />
      </div>

      <!-- Alterna entre Login e Registro -->
      <div class="col-12 q-pa-xl">
        <div v-if="isLogin">
          <LoginForm />
        </div>
        <div v-else>
          <RegisterForm />
        </div>
      </div>

      <div class="col-12 q-pb-xl">
        <div v-if="isLogin">
          Não tem uma conta? <span @click="toggleForm" class="cursor-pointer">Cadastre-se</span>
        </div>
        <div v-else>
          Já possui uma conta? <span @click="toggleForm" class="cursor-pointer">Faça login</span>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import LoginForm from 'src/components/Auth/LoginForm.vue'
import RegisterForm from 'src/components/Auth/RegisterForm.vue'

const isLogin = ref(true)

const toggleForm = () => {
  isLogin.value = !isLogin.value
}
</script>

<style lang="scss" scoped>
.authBox {
  width: 90vw;
  min-width: 35vw;
  max-width: 500px;
  border-radius: 6px;

  .logo {
    width: 100%;
    max-width: 150px;
  }

  span {
    transition: all 0.3s ease;
    color: $primary;

    &:hover {
      color: $secondary;
    }
  }

  @media (max-width: 768px) {
    .authBox {
      min-width: 75vw;
    }
  }

  @media (max-width: 480px) {
    .authBox {
      min-width: 90vw;
    }

    .q-pa-xl {
      padding: 20px;
    }
  }
}
</style>
