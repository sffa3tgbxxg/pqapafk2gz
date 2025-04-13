import { defineStore } from 'pinia'

export const useNotification = defineStore('notification', {
  state: () => ({
    message: '',
    type: 'success',
    visible: false,
  }),
  actions: {
    showNotification(message, type = 'error', duration = 3000) {
      this.message = message
      this.type = type
      this.visible = true


      setTimeout(() => {
        this.visible = false
      }, duration)
    },
    clear() {
      this.visible = false
    },
  },
})
