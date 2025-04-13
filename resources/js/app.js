import '../css/main.css' // Если у тебя есть стили, оставь
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue' // Укажи корневой компонент
import router from './router/index.js' // Подключи маршруты Vue Router
import '@/bootstrap.js'
import { useUserStore } from '@/stores/user.js'

const pinia = createPinia()
const app = createApp(App)

app.use(pinia)
app.use(router)

const userStore = useUserStore()

userStore.fetchUser()
setInterval(() => {
  if (userStore.authToken !== null) {
    userStore.fetchUser()
  }
}, 10000)

app.mount('#app')
