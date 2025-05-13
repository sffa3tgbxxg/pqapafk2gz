import { ref } from "vue";
import { format } from "date-fns";
import axiosInstance from "@/bootstrap.js";
import { useNotification } from "../store/Notification";

export function usePhpLogsService() {
  const notification = useNotification();
  const formSearch = ref({
    from: format(new Date().setMonth(new Date().getMonth() - 1), "dd.MM.yyyy 00:00:00"),
    to: format(new Date(), "dd.MM.yyyy 23:59:59"),
    page: 1,
  });

  const getLogs = async () => {
    try {
      const response = await axiosInstance.get("/logs/php", {
        params: formSearch.value,
      });
      return response.data;
    } catch (error) {
      notification.showNotification(error.response.data.message);
    }
  };

  return {
    formSearch,
    getLogs,
  };
}
