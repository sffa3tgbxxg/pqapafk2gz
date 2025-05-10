<script lang="ts">
import { computed, defineComponent } from 'vue'
import { useNotification } from '@/store/Notification'

export default defineComponent({
  name: 'NotificationComponent',
  setup() {
    const store = useNotification()

    const isVisible = computed(() => store.visible)
    const type = computed(() => store.type)

    return {
      store,
      isVisible,
      type,
    }
  },
})
</script>

<template>
  <div v-if="isVisible" class="zlk34n1">
    <div :class="['zlk34n2', type]">
      <div>
        <div class="zlk34n3">
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
        class="zlk34n4"
      >
        <path
          fill="currentColor"
          d="M6.293 6.293a1 1 0 0 1 1.414 0L12 10.586l4.293-4.293a1 1 0 1 1 1.414 1.414L13.414 12l4.293 4.293a1 1 0 0 1-1.414 1.414L12 13.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L10.586 12 6.293 7.707a1 1 0 0 1 0-1.414"
        ></path>
      </svg>
    </div>
  </div>
</template>
