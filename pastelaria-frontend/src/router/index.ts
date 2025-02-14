import { defineRouter } from '#q-app/wrappers'
import {
  createMemoryHistory,
  createRouter,
  createWebHashHistory,
  createWebHistory,
} from 'vue-router'
import { useAuthStore } from 'src/stores/auth-store'
import routes from './routes'

export default defineRouter(function () {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : process.env.VUE_ROUTER_MODE === 'history'
      ? createWebHistory
      : createWebHashHistory

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,
    history: createHistory(process.env.VUE_ROUTER_BASE),
  })

  // Middleware de autenticação antes de cada navegação
  Router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    const token = authStore.token || localStorage.getItem('token')

    if (to.meta.requiresAuth && !token) {
      // Redireciona para login se o usuário não estiver autenticado
      next({ name: 'Login' })
    } else if (to.name === 'Login' && token) {
      // Se já está logado e tenta acessar a tela de login, redireciona para o app
      next({ name: 'App' })
    } else {
      next()
    }
  })

  return Router
})
