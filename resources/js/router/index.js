import LoginPage from '@/views/auth/LoginPage.vue'
import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/views/home/HomePage.vue'
import ServicesPage from '@/views/services/ServicesPage.vue'
import ServicesExchangersPage from '@/views/service-exchangers/ServicesExchangersPage.vue'
import EmployeesPage from '@/views/employees/EmployeesPage.vue'
import InvoicesPage from '@/views/invoices/InvoicesPage.vue'
import ExchangersPage from '@/views/exchangers/ExchangersPage.vue'
import SubscriptionPage from '@/views/subscriptions/SubscriptionPage.vue'
import WithdrawsPage from '@/views/withdraws/WithdrawsPage.vue'

const routes = [
  { path: '/auth', component: LoginPage, name: 'Auth' },
  { path: '/', component: HomePage, name: 'Home' },
  { path: '/services', component: ServicesPage, name: 'Services' },
  { path: '/service-exchangers', component: ServicesExchangersPage, name: 'ServiceExchangers' },
  { path: '/employees', component: EmployeesPage, name: 'Employees' },
  { path: '/invoices', component: InvoicesPage, name: 'Invoices' },
  { path: '/exchangers', component: ExchangersPage, name: 'Exchangers' },
  { path: '/subscription', component: SubscriptionPage, name: 'Subscription' },
  { path: '/withdraws', component: WithdrawsPage, name: 'Withdraws' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
