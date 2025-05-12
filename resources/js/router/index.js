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

const routes = [
  { path: "/auth", component: LoginPage, name: "Auth", meta: { requiresAuth: false } },
  // { path: "/", component: HomePage, name: "Home", meta: { requiresAuth: true } },
  { path: "/", component: ServicesExchangersPage, name: "Home", meta: { requiresAuth: true } },
  { path: "/services", component: ServicesPage, name: "Services", meta: { requiresAuth: true } },
  {
    path: "/service-exchangers",
    component: ServicesExchangersPage,
    name: "ServiceExchangers",
    meta: { requiresAuth: true },
  },
  {
    path: "/employees",
    component: EmployeesPage,
    name: "Employees",
    meta: { requiresAuth: true },
  },
  { path: "/invoices", component: InvoicesPage, name: "Invoices", meta: { requiresAuth: true } },
  {
    path: "/exchangers",
    component: ExchangersPage,
    name: "Exchangers",
    meta: { requiresAuth: true },
  },
  {
    path: "/subscription",
    component: SubscriptionPage,
    name: "Subscription",
    meta: { requiresAuth: true },
  },
  {
    path: "/withdraws",
    component: WithdrawsPage,
    name: "Withdraws",
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem("token")) {
    next({ name: "Auth" });
  } else {
    next();
  }
});

export default router;
