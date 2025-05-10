import { ref } from 'vue'
import axiosInstance from '@/bootstrap.js'
import { useNotification } from '../store/Notification'

export function useServiceExchangers() {
  const notification = useNotification()
  const formSearch = ref({
    payment_method: '',
    service: '',
  })

  const formCreate = ref({
    service_id: null,
    exchanger_id: null,
    api_key: null,
    secret_key: null,
  })

  const formEdit = ref({
    id: null,
    api_key: null,
    secret_key: null,
    fee: null,
    active: false,
  })

  const formEditExchanger = ref({
    name: null,
    fee: null,
  })

  const showCreateExchangerService = ref(false)
  const showEditExchangerService = ref(false)

  const createExchangerService = async () => {
    try {
      await axiosInstance.post('/service-exchangers/', formCreate.value)
      showCreateExchangerService.value = false
      location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const updateExchangerService = async (form) => {
    try {
      await axiosInstance.put(`/service-exchangers/${form.id}`, form)
      if (showEditExchangerService.value) {
        showEditExchangerService.value = false
        location.reload()
      }
    } catch (error) {
      console.info(errror)
      notification.showNotification(error.response.data.message)
    }
  }
  const editExchangerService = (exchangerService, openModal = true) => {
    formEdit.value.id = exchangerService.id
    formEdit.value.api_key = exchangerService.api_key
    formEdit.value.active = exchangerService.active
    formEdit.value.fee = exchangerService.fee.service
    formEdit.value.secret_key = exchangerService.secret_key
    formEditExchanger.value.name = exchangerService.exchanger_name
    formEditExchanger.value.fee = exchangerService.fee.exchanger
    if (openModal) {
      showEditExchangerService.value = true
    }
  }

  const getServiceExchangers = async () => {
    try {
      const response = await axiosInstance.get('/service-exchangers/', { params: formSearch.value })
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  return {
    formEditExchanger,
    formCreate,
    formEdit,
    formSearch,
    showCreateExchangerService,
    showEditExchangerService,
    createExchangerService,
    updateExchangerService,
    getServiceExchangers,
    editExchangerService,
  }
}
