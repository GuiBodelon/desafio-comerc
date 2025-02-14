import { defineStore } from 'pinia'
import { ref, watch } from 'vue'
import { api } from 'boot/axios'
import { useRouter } from 'vue-router'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || '')
  const isRedirecting = ref(false) // Flag para impedir redirecionamento em loop

  interface ApiError {
    response?: {
      data?: {
        message?: string
      }
    }
  }

  const router = useRouter() // Agora usamos o router aqui dentro do store

  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  const login = async (email: string, password: string) => {
    try {
      const response = await api.post('auth/login', { email, password })
      user.value = response.data.user
      token.value = response.data.token

      localStorage.setItem('token', token.value)
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`

      return { success: true }
    } catch (error) {
      const err = error as ApiError
      return { success: false, message: err.response?.data?.message || 'Erro ao fazer login' }
    }
  }

  const register = async ({
    name,
    email,
    password,
    password_confirmation,
  }: {
    name: string
    email: string
    password: string
    password_confirmation: string
  }) => {
    try {
      const response = await api.post('auth/register', {
        name,
        email,
        password,
        password_confirmation,
      })

      user.value = response.data.user
      token.value = response.data.token

      localStorage.setItem('token', token.value)
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`

      return { success: true }
    } catch (error) {
      const err = error as ApiError
      return {
        success: false,
        message: err.response?.data?.message || 'Erro ao realizar o registro',
      }
    }
  }

  const logout = async () => {
    if (isRedirecting.value) return // Impede o loop se já está redirecionando

    try {
      isRedirecting.value = true // Define a flag de redirecionamento
      await api.post(
        'auth/logout',
        {},
        {
          headers: { Authorization: `Bearer ${token.value}` },
        },
      )
    } catch (error) {
      console.error('Erro ao tentar deslogar:', error)
    } finally {
      user.value = null
      token.value = ''
      localStorage.removeItem('token')
      delete api.defaults.headers.common['Authorization']

      if (isRedirecting.value) {
        // Redireciona para a tela de login após logout
        await router.push({ name: 'Login' }) // Redireciona apenas se não estiver redirecionando já
      }

      // Reseta a flag após o logout
      isRedirecting.value = false
    }
  }

  watch(token, (newToken) => {
    if (newToken) {
      localStorage.setItem('token', newToken)
      api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
    } else {
      localStorage.removeItem('token')
      delete api.defaults.headers.common['Authorization']
    }
  })

  return { user, token, login, register, logout }
})
