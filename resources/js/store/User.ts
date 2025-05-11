import { defineStore } from "pinia";
import axiosInstance from "@/bootstrap.js";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: localStorage.getItem("user") || null,
  }),
  actions: {
    async fetchUserInfo() {
      try {
        const { data } = await axiosInstance.get("/me");
        this.setUser(data.data);
      } catch (error) {
        this.clearUser();
      }
    },
    setUser(user) {
      localStorage.setItem("user", user);
    },
    clearUser() {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      this.user = null;
    },
  },
});
