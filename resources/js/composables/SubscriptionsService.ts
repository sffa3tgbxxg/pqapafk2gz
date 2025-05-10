import { ref } from 'vue'
import { useNotification } from '@/store/Notification'
import axiosInstance from '@/bootstrap'

export function useSubscriptionService() {
  const showModalPayment = ref(false)
  const showInfoModal = ref(false)
  const targetInvoice = ref(null)
  const isLoadingPayment = ref(false)
  const notification = useNotification()

  const getInvoices = async () => {
    try {
      const response = await axiosInstance.get('/account')
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const generateInvoice = async () => {
    if (isLoadingPayment.value) return false
    isLoadingPayment.value = true

    try {
      const response = await axiosInstance.post('/account/payment')
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    } finally {
      isLoadingPayment.value = false
    }
  }

  const showInvoice = async (invoiceId) => {
    if (isLoadingPayment.value) return false
    isLoadingPayment.value = true

    try {
      const response = await axiosInstance.get(`/account/${invoiceId}`)
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    } finally {
      isLoadingPayment.value = false
    }
  }

  const generateQr = async (text) => {
    try {
      const { data } = await axiosInstance.get('/settings/qr', {
        params: {
          target: text,
        },
      })
      return data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  return {
    generateInvoice,
    getInvoices,
    generateQr,
    showInvoice,
    showModalPayment,
    showInfoModal,
    targetInvoice,
    isLoadingPayment,
  }
}
