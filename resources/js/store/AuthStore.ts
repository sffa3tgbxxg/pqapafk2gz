import { defineStore } from "pinia";
import { ref } from "vue";
import axios from "axios";

export const useAuthStore = defineStore("auth", () => {
  const isAuthenticated = ref(false);
  const user = ref(null);
  const token = ref(null);

  async function login(credentials) {
    try {
      const response = await axios.post("/api/auth/login", credentials);
      isAuthenticated.value = true;
      user.value = data.user;
      token.value = response.data.token;
      return response.data;
    } catch (error) {
      console.error("Login failed:", error);
      throw error;
    }
  }

  function logout() {
    isAuthenticated.value = false;
    user.value = null;
  }

  return { isAuthenticated, user, login, logout };
});
