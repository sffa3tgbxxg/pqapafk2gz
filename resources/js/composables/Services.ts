import { ref } from 'vue'
import axiosInstance from '@/bootstrap.js'
import { useNotification } from '../store/Notification'

export function useServices() {
  const notification = useNotification()
  const showEditService = ref(false)
  const showCreateService = ref(false)

  const formSearch = ref({
    id: '',
    name: '',
  })

  const formCreate = ref({
    name: '',
  })

  const formEdit = ref({
    id: null,
    name: '',
    active: false,
  })

  const editService = (service) => {
    showEditService.value = true
    formEdit.value.id = service.id
    formEdit.value.name = service.name
    formEdit.value.active = service.active
  }
  const createService = async () => {
    try {
      await axiosInstance.post('/services', formCreate.value)
      showCreateService.value = false
      await location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const updateService = async (service) => {
    try {
      await axiosInstance.put(`/services/${service.id}`, {
        name: service.name,
        active: service.active,
      })
      showEditService.value = false
      await location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const test = ref(true)

  const getServices = async (limit = true) => {
    try {
      const response = await axiosInstance.get('/services', { with_limit: limit })
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  return {
    editService,
    showEditService,
    showCreateService,
    formSearch,
    formCreate,
    formEdit,
    test,
    createService,
    updateService,
    getServices,
  }
}
