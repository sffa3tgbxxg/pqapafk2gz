import { useNotification } from '../store/Notification'
import axiosInstance from '@/bootstrap.js'
import { ref } from 'vue'

export function useEmployee() {
  const showEditModal = ref(false)
  const showCreateModal = ref(false)
  const showDeleteModal = ref(false)
  const rolesEdit = ref(null)

  const formDelete = ref({
    id: null,
    login: null,
  })

  const formEdit = ref({
    id: null,
    role: null,
    role_name: null,
    comment: null,
    contacts: null,
  })

  const formSearch = ref({
    service_id: null,
    role: null,
  })

  const formCreate = ref({
    service_id: null,
    login: null,
    role: null,
    comment: null,
    contacts: null,
  })

  const notification = useNotification()
  const getEmployees = async () => {
    try {
      const response = await axiosInstance.get('/employees')
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const createEmployee = async () => {
    try {
      await axiosInstance.post(`/employees`, formCreate.value)
      location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const updateEmployee = async (form) => {
    try {
      await axiosInstance.put(`/employees/${form.id}`, form)
      showEditModal.value = false
      location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const deleteEmployee = async (employee, verified = true) => {
    if (verified) {
      formDelete.value.id = employee.id
      formDelete.value.login = employee.login
      showDeleteModal.value = true
      return
    }
    try {
      await axiosInstance.delete(`/employees/${employee.id}`)
      showDeleteModal.value = false
      location.reload()
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const updateRolesCreate = async (serviceId) => {
    try {
      const response = await axiosInstance.get('/settings/roles', {
        params: { service_id: serviceId },
      })
      return response.data
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  const editEmployee = async (employee) => {
    rolesEdit.value = null
    formEdit.value.id = employee.id
    formEdit.value.role_name = employee.role
    formEdit.value.role = employee.role_code
    formEdit.value.comment = employee.comment
    formEdit.value.contacts = employee.contacts
    showEditModal.value = true

    rolesEdit.value = (await updateRolesCreate(employee.service_id)).data
  }

  return {
    showCreateModal,
    showEditModal,
    showDeleteModal,
    formEdit,
    formCreate,
    formDelete,
    formSearch,
    rolesEdit,
    createEmployee,
    getEmployees,
    updateEmployee,
    editEmployee,
    updateRolesCreate,
    deleteEmployee,
  }
}
