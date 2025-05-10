import { defineStore } from 'pinia'
import axiosInstance from '@/bootstrap'
import { useNotification } from './Notification'

export const useMenuStore = defineStore('menu', {
  state: () => ({
    menu: null,
  }),
  actions: {
    async loadMenu() {
      try {
        const response = await axiosInstance.get('/settings/menu')
        this.menu = response.data.data
      } catch (error) {
        console.error(error)
        useNotification().showNotification(error.response.data.message)
      }
    },
  },

  getters: {
    getMenu: (state) => state.menu,
  },
})
