import LoginPage from "@/views/auth/LoginPage.vue";
import { createRouter, createWebHistory } from "vue-router";
import HomePage from "@/views/home/HomePage.vue";
import ServicesPage from "@/views/services/ServicesPage.vue";
import ServicesExchangersPage from "@/views/service-exchangers/ServicesExchangersPage.vue";
import EmployeesPage from "@/views/employees/EmployeesPage.vue";
import InvoicesPage from "@/views/invoices/InvoicesPage.vue";
import ExchangersPage from "@/views/exchangers/ExchangersPage.vue";
import SubscriptionPage from "@/views/subscriptions/SubscriptionPage.vue";
import WithdrawsPage from "@/views/withdraws/WithdrawsPage.vue";
import { useAuthStore } from "@/store/AuthStore.js";

const routes = [
  { path: "/auth", component: LoginPage, name: "Auth", meta: { requiresAuth: false } },
  { path: "/", component: HomePage, name: "Home", meta: { requiresAuth: false } },
  { path: "/services", component: ServicesPage, name: "Services", meta: { requiresAuth: false } },
  {
    path: "/service-exchangers",
    component: ServicesExchangersPage,
    name: "ServiceExchangers",
    meta: { requiresAuth: false },
  },
  {
    path: "/employees",
    component: EmployeesPage,
    name: "Employees",
    meta: { requiresAuth: false },
  },
  { path: "/invoices", component: InvoicesPage, name: "Invoices", meta: { requiresAuth: false } },
  {
    path: "/exchangers",
    component: ExchangersPage,
    name: "Exchangers",
    meta: { requiresAuth: false },
  },
  {
    path: "/subscription",
    component: SubscriptionPage,
    name: "Subscription",
    meta: { requiresAuth: false },
  },
  {
    path: "/withdraws",
    component: WithdrawsPage,
    name: "Withdraws",
    meta: { requiresAuth: false },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: "Auth", query: { redirect: to.fullPath } });
  } else {
    next();
  }
});

export default router;
