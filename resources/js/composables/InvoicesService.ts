import { useNotification } from '../store/Notification'
import axiosInstance from '@/bootstrap.js'
import { ref } from 'vue'
import { format } from 'date-fns'

export function useInvoices() {
  const showCancelModal = ref(false)
  const showAcceptModal = ref(false)
  const showInfoModal = ref(false)

  const targetInvoice = ref(null)

  const commentForm = ref({
    comment: '',
  })

  const formSearch = ref({
    from: format(new Date().setMonth(new Date().getMonth() - 1), 'dd.MM.yyyy'),
    to: format(new Date(), 'dd.MM.yyyy'),
    service_id: null,
    exchanger_id: null,
    user: null,
    page: 1,
  })

  const notification = useNotification()

  const getInvoices = async () => {
    try {
      const response = await axiosInstance.get('/invoices', { params: formSearch.value })
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const cancel = async (id) => {
    try {
      await axiosInstance.put(`/invoices/${id}`, commentForm.value)
      showCancelModal.value = false
      location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const accept = async (id) => {
    try {
      await axiosInstance.put(`/invoices/${id}/verified`, commentForm.value)
      showAcceptModal.value = false
      location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  return {
    accept,
    cancel,
    getInvoices,
    formSearch,
    showCancelModal,
    showAcceptModal,
    showInfoModal,
    targetInvoice,
    commentForm,
  }
}
