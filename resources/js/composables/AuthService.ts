import { ref } from 'vue'
import axios from 'axios'
import { useNotification } from '@/stores/notification.js'
import { useUserStore } from '@/stores/user.js'
import { useRouter } from 'vue-router'

export function authService() {
  const userStore = useUserStore();
  const router = useRouter()
  const notificationStore = useNotification()
  const isLoading = ref(false)
  const auth = async (form: object, action: string) => {
    if (isLoading.value) return
    isLoading.value = true

    try {
      const response = await axios.post(`/api/auth/${action}`, form)
      localStorage.setItem('auth-token', response.data.token);
      // await useUserStore.setUser(response.data.);
      await router.push({ name: 'dashboard' })
    } catch (error) {
      await notificationStore.showNotification(error.response.data.message)
    } finally {
      isLoading.value = false
    }
  }

  return {
    isLoading,
    auth,
  }
}
