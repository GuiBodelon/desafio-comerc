<template>
  <q-page class="bg-dark row justify-center items-center">
    <div class="authBox shadow-4 bg-white text-center q-pa-none q-ma-none">
      <div class="col-12 flex column justify-center items-center q-pa-sm">
        <q-img src="logo-pastel.png" class="logoPastel" />
        <div class="text-h4">Pastelaria</div>
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
      <div class="col-12 flex column justify-center items-center bg-dark q-pa-lg text-center">
        <q-img src="logo.webp" class="logoComerc" />
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
  border-radius: 10px;

  .logoPastel {
    width: 100%;
    max-width: 150px;
  }

  .logoComerc {
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
