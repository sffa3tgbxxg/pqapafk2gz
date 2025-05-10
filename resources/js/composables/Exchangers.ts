import { useNotification } from '../store/Notification'
import axiosInstance from '@/bootstrap.js'
import { ref } from 'vue'

export function useExchangers() {
  const showCreateModal = ref(false)
  const showEditModal = ref(false)
  const notification = useNotification()
  const formSearch = ref({
    name: null,
  })
  const formCreate = ref({
    name: null,
    fee: null,
    endpoint: null,
    min_amount: null,
    max_amount: null,
    min_withdraw: null,
    auto_withdraw: false,
  })

  const formEdit = ref({
    id: null,
    name: null,
    fee: null,
    endpoint: null,
    min_amount: null,
    max_amount: null,
    min_withdraw: null,
    auto_withdraw: false,
  })

  const getExchangersByService = async (serviceId) => {
    try {
      const response = await axiosInstance.get('/exchangers/by-service', {
        params: {
          service_id: serviceId,
        },
      })
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const getExchangers = async () => {
    try {
      const response = await axiosInstance.get('/exchangers', {
        params: formSearch.value,
      })
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const edit = (exchanger) => {
    showEditModal.value = true
    formEdit.value = exchanger
  }

  const update = async (form, reload = false) => {
    try {
      await axiosInstance.put(`/exchangers/${form.id}`, form)
      if (reload) {
        location.reload()
      }
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const create = async () => {
    try {
      const response = await axiosInstance.post(`/exchangers`, formCreate.value)
      showCreateModal.value = false
      location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  return {
    formSearch,
    formCreate,
    formEdit,
    showCreateModal,
    showEditModal,
    getExchangersByService,
    getExchangers,
    update,
    edit,
    create,
  }
}
