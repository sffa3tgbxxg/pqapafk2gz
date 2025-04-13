import axiosInstance from '@/bootstrap'
import { ref } from 'vue'
import { useNotification } from '@/stores/notification.js'
import { useRouter } from 'vue-router'

export function accountService() {
  const notificationStore = useNotification()
  const isLoading = ref(false)
  const router = useRouter()
  const generate = async () => {
    if (isLoading.value) return
    isLoading.value = true

    try {
      const response = await axiosInstance.post('/account/payment')
      await router.push({ name: 'accountInvoice', params: { id: response.data.data.id } })
    } catch (error) {
      await notificationStore.showNotification(error.response.data.message)
    } finally {
      isLoading.value = false
    }
  }

  const get = async (invoiceId): object => {
    if (isLoading.value) return
    isLoading.value = true

    try {
      const response = await axiosInstance.get(`/account/${invoiceId}`)
      return response.data.data
    } catch (error) {
      await notificationStore.showNotification(error.response.data.message)
    } finally {
      isLoading.value = false
    }
  }

  const list = async (page = 1): object => {
    if (isLoading.value) return
    isLoading.value = true

    try {
      const response = await axiosInstance.get(`/account`, { params: { page: page } })
      return response.data
    } catch (error) {
      await notificationStore.showNotification(error.response.data.message)
    } finally {
      isLoading.value = false
    }
  }

  return {
    generate,
    get,
    list,
  }
}
