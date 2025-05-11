import axios from "axios";
import { useAuthStore } from "@/store/AuthStore.js";
import { useRouter } from "vue-router";

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const apiBaseUrl = import.meta.env.VITE_APP_URL || "http://localhost:8000/api";

const authStore = useAuthStore();

const instance = axios.create({
  baseURL: apiBaseUrl,
  headers: {
    "Content-Type": "application/json",
  },
});

instance.interceptors.request.use(
  (config) => {
    const token = authStore.token;
    if (token && authStore.isAuthenticated) {
      config.headers["Authorization"] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  },
);

instance.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      authStore.logout();
      useRouter().push({ name: "Auth" });
    }
    return Promise.reject(error);
  },
);

export default instance;
