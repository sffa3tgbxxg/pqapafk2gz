import axios from "axios";
import { useNotification } from "../store/Notification";
import { useAuthStore } from "../store/AuthStore";

export function useAuth() {
  const notification = useNotification();
  const authStore = useAuthStore();

  const form = {
    login: "",
    password: "",
  };

  const login = async () => {
    try {
      await authStore.login(form);
      router.push("/");
    } catch (error) {
      notification.showNotification(error.response.data.message);
    }
  };

  return {
    form,
    login,
  };
}
