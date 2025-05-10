import axios from 'axios'
import { useNotification } from '../store/Notification'

export function useAuth() {
  const notification = useNotification()

  const form = {
    login: '',
    password: '',
  }

  const login = async () => {
    try {
      const response = await axios.post('/api/auth/login', form)
      localStorage.setItem('token',response.data.token);
    } catch (error) {
      notification.showNotification(error.response.data.message)
    }
  }

  return {
    form,
    login,
  }
}
