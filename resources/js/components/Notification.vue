<script setup>
import { useNotification } from '@/stores/notification.js'
import { computed } from 'vue'

const store = useNotification()

const isVisible = computed(() => store.visible)
</script>

<template>
  <div v-if="isVisible" class="bar-notification">
    <div class="bar-notification2">
      <div>
        <div class="bar-notification3">
          <div>
            <div>
              {{ store.message }}
            </div>
          </div>
        </div>
      </div>
      <svg
        @click="store.clear()"
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        class="bar-notification4"
      >
        <path
          fill="currentColor"
          d="M6.293 6.293a1 1 0 0 1 1.414 0L12 10.586l4.293-4.293a1 1 0 1 1 1.414 1.414L13.414 12l4.293 4.293a1 1 0 0 1-1.414 1.414L12 13.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L10.586 12 6.293 7.707a1 1 0 0 1 0-1.414"
        ></path>
      </svg>
    </div>
  </div>
</template>

<style scoped>
.bar-notification {
  justify-items: end;
  display: grid;
  gap: 8px;
  z-index: 10000000;
  right: 14px;
  top: 14px;
  position: fixed;
}

.bar-notification2 {
  width: 460px;
  font-size: 15px;
  letter-spacing: 0;
  line-height: 24px;
  text-decoration: none;
  text-transform: none;
  font-variant-numeric: normal;
  font-weight: 500;
  transition: opacity 0.3s;
  box-shadow:
    0 0 2px rgba(29, 49, 74, 0.1019),
    0 4px 20px rgba(29, 49, 74, 0.149);
  max-width: 556px;
  background: linear-gradient(90deg, rgb(249 97 134 / 100%) 4px, rgb(32 49 72 / 100%) 0);
  border-radius: 8px;
  box-sizing: border-box;
  color: rgb(255 255 255 / 95%);
  display: grid;
  padding: 14px 28px 14px 24px;
  position: relative;
  word-break: break-word;
}

.bar-notification3 {
  display: flex;
  gap: 16px;
  flex-direction: column;
}

.bar-notification4 {
  padding: 4px;
  position: absolute;
  right: 4px;
  top: 4px;
  box-sizing: initial;
  color: rgb(255 255 255 / 80%);
  cursor: pointer;
  display: block;
}
</style>
