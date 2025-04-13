import { defineStore } from 'pinia'
import axiosInstance from '@/bootstrap.js'
import router from '@/router/index.js'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: localStorage.getItem('user') || null,
    authToken: localStorage.getItem('auth-token') || null,
  }),
  actions: {
    async fetchUser() {
      try {
        const response = await axiosInstance.get('/me')
        this.setUser(response.data)
      } catch (error) {
        console.error('Fail to fetch user', error)
        this.clearUser()
        if (!['register','login'].includes(router.currentRoute.value.name)){
          await router.push({ name: 'login' })
        }
      }
    },
    setUser(user) {
      localStorage.setItem('user', user)
    },
    clearUser() {
      localStorage.removeItem('user')
      this.user = null
      localStorage.removeItem('auth-token')
    },
  },
})
