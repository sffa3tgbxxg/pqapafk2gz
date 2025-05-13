import { useNotification } from "../store/Notification";
import axiosInstance from "@/bootstrap.js";
import { ref } from "vue";
import { format } from "date-fns";

export function useInvoices() {
  const showCancelModal = ref(false);
  const showAcceptModal = ref(false);
  const showInfoModal = ref(false);
  const editForm = ref({
    id: null,
    exchanger_name: null,
    date: null,
    amount: null,
    comment: null,
    status: {
      name: null,
      code: null,
    },
  });

  const targetInvoice = ref(null);

  const commentForm = ref({
    comment: "",
  });

  const formSearch = ref({
    from: format(new Date().setMonth(new Date().getMonth() - 1), "dd.MM.yyyy"),
    to: format(new Date(), "dd.MM.yyyy"),
    service_id: null,
    exchanger_id: null,
    user: null,
    page: 1,
    problem: 0,
  });

  const notification = useNotification();

  const getInvoices = async () => {
    try {
      const response = await axiosInstance.get("/invoices", { params: formSearch.value });
      return response.data;
    } catch (error) {
      notification.showNotification(error.response.data.message);
    }
  };

  const cancel = async (id) => {
    try {
      await axiosInstance.put(`/invoices/${id}/cancel`, commentForm.value);
      showCancelModal.value = false;
      location.reload();
    } catch (error) {
      notification.showNotification(error.response.data.message);
    }
  };

  const accept = async (id) => {
    try {
      await axiosInstance.put(`/invoices/${id}/verified`, commentForm.value);
      showAcceptModal.value = false;
      location.reload();
    } catch (error) {
      notification.showNotification(error.response.data.message);
    }
  };

  const update = async () => {
    try {
      await axiosInstance.put(`/invoices/${editForm.value.id}`, {
        status: editForm.value.status.code,
        comment: editForm.value.comment,
      });
      showInfoModal.value = false;
      // location.reload();
    } catch (error) {
      notification.showNotification(error.response.data.message);
    }
  };

  const getStatuses = async () => {
    try {
      const { data } = await axiosInstance.get(`/settings/statuses`);
      return data;
    } catch (error) {
      notification.showNotification(error.response.data.message);
    }
  };

  const edit = async (invoice) => {
    editForm.value.id = invoice.id;
    editForm.value.date = invoice.time.created_at_format;
    editForm.value.amount = invoice.amount.out;
    editForm.value.exchanger_name = invoice.payment_method?.name;
    editForm.value.status.name = invoice.status.name;
    editForm.value.status.code = invoice.status.code;
    editForm.value.comment = invoice.comment;
  };

  return {
    update,
    accept,
    cancel,
    getInvoices,
    edit,
    getStatuses,
    formSearch,
    showCancelModal,
    showAcceptModal,
    showInfoModal,
    targetInvoice,
    commentForm,
    editForm,
  };
}
