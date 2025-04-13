import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/views/HomeView.vue'
import Account from '@/views/AccountView.vue'

const routes = [
  {
    meta: {
      title: 'Аккаунт',
    },
    path: '/account',
    name: 'account',
    component: Account,
  },
  {
    meta: {
      title: 'Статистика',
    },
    path: '/dashboard',
    name: 'dashboard',
    component: Home,
  },
  {
    meta: {
      title: 'Счет на оплату',
    },
    path: '/account-invoice/:id',
    name: 'accountInvoice',
    component: () => import('@/views/AccountInvoiceView.vue'),
  },
  {
    meta: {
      title: 'Ваши сервисы',
    },
    path: '/services',
    name: 'services',
    component: () => import('@/views/ServicesView.vue'),
  },
  {
    meta: {
      title: 'Forms',
    },
    path: '/forms',
    name: 'forms',
    component: () => import('@/views/FormsView.vue'),
  },
  {
    meta: {
      title: 'Profile',
    },
    path: '/profile',
    name: 'profile',
    component: () => import('@/views/ProfileView.vue'),
  },
  {
    meta: {
      title: 'Ui',
    },
    path: '/ui',
    name: 'ui',
    component: () => import('@/views/UiView.vue'),
  },
  {
    meta: {
      title: 'Responsive layout',
    },
    path: '/responsive',
    name: 'responsive',
    component: () => import('@/views/ResponsiveView.vue'),
  },
  {
    meta: {
      title: 'Вход',
    },
    path: '/login',
    name: 'login',
    component: () => import('@/views/LoginView.vue'),
  },
  {
    meta: {
      title: 'Регистрация',
    },
    path: '/register',
    name: 'register',
    component: () => import('@/views/RegisterView.vue'),
  },
  {
    meta: {
      title: 'Error',
    },
    path: '/error',
    name: 'error',
    component: () => import('@/views/ErrorView.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  const isAuth = localStorage.getItem('auth-token');
  const isAuthRoute = ['register', 'login'].includes(to.name)
  if (!isAuth && !isAuthRoute) {
    next({ name: 'login' })
  } else if (isAuth && isAuthRoute) {
    next({ name: 'dashboard' })
  } else {
    next()
  }
})

export default router
