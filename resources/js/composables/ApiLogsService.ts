import { ref } from "vue";
import { format } from "date-fns";
import axiosInstance from "@/bootstrap.js";
import { useNotification } from "../store/Notification";

export function useApiLogsService() {
  const notification = useNotification();
  const formSearch = ref({
    from: format(new Date().setMonth(new Date().getMonth() - 1), "dd.MM.yyyy 00:00:00"),
    to: format(new Date(), "dd.MM.yyyy 23:59:59"),
    invoice_id: null,
    exchanger_null: null,
    page: 1,
  });

  const getLogs = async () => {
    try {
      const response = await axiosInstance.get("/logs/api", {
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
