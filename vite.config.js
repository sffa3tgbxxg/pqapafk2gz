import {fileURLToPath, URL} from 'node:url'

import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig({
  server: {
    port: 3001,
    host: '127.0.0.1',
  },
  plugins: [
    laravel({
      input: ['resources/css/main.css','resources/js/app.js'],
      refresh: true,
    }),
    vue(),
    vueDevTools(),
    tailwindcss()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
    },
  },

})
