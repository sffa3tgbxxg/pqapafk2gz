import axios from "axios";
import { useNotification } from "../store/Notification";
import { useRouter } from "vue-router";

export function useAuth() {
  const notification = useNotification();
  const router = useRouter();
  const form = {
    login: "",
    password: "",
  };

  const login = async () => {
    try {
      const response = await axios.post("/api/auth/login", form);
      localStorage.setItem("token", response.data.token);
      await router.push({ name: "Home" });
      location.reload();
    } catch (error) {
      console.error(error);
      if (error.response?.data) {
        notification.showNotification(error.response?.data?.message);
      }
    }
  };

  return {
    form,
    login,
  };
}
