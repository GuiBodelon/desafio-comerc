import type { RouteRecordRaw } from 'vue-router'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '/',
        name: 'Login',
        component: () => import('pages/Auth/AuthPage.vue'),
        meta: { requiresAuth: false },
      },
      {
        path: '/pedidos',
        component: () => import('pages/NoAuth/OrdersPage.vue'),
        name: 'orders',
        meta: {
          requiresAuth: false, // Não precisa de autenticação para clientes
        },
      },
    ],
  },
  {
    path: '/pastelaria',
    name: 'App',
    component: () => import('layouts/AppLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '/clientes',
        name: 'Clientes',
        component: () => import('pages/ClientsPage.vue'),
      },
      {
        path: '/produtos',
        name: 'Produtos',
        component: () => import('pages/ProductsPage.vue'),
      },
      /*
      {
        path: '/pedidos',
        name: 'Pedidos',
        component: () => import('pages/OrdersPage.vue'),
      },
      */
    ],
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
