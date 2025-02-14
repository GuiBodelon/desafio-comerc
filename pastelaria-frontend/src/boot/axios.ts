import { defineBoot } from '#q-app/wrappers'
import axios, { type AxiosInstance } from 'axios'
import { useAuthStore } from 'src/stores/auth-store'

declare module 'vue' {
  interface ComponentCustomProperties {
    $axios: AxiosInstance
    $api: AxiosInstance
  }
}

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/',
  withCredentials: true, // Necessário para Sanctum (cookies de sessão)
})

const setAuthToken = () => {
  const token = localStorage.getItem('token')
  if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }
}

setAuthToken() // Configura o token ao iniciar

// Interceptor para capturar erros 401 (token expirado)
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response && error.response.status === 401) {
      const authStore = useAuthStore()
      // Limpa o token e realiza o logout
      await authStore.logout() // Aguarda o logout completar
    }
    return Promise.reject(new Error(error)) // Garante que o erro é do tipo Error
  },
)

export default defineBoot(({ app }) => {
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
})

export { api, setAuthToken }
