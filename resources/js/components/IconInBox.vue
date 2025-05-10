<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'IconInBox',
  props: {
    text: {
      type: String,
      default: '',
    },
    width: {
      type: Number,
      default: 14,
    },
    height: {
      type: Number,
      default: 14,
    },
    borderColor: {
      type: String,
      default: '#bdbdbd',
    },
    color:{
      type: String,
      default: '#656464'
    },
    icon: {
      type: String,
    },
  },
})
</script>

<template>
  <div class="gkl1" :style="{ border: `1px solid ${borderColor}`, color: color }">
    <component v-if="icon"
      :style="{ width: `${width}px`, height: `${height}px`, color: '#656464' }"
      :is="icon"
    />
    <span v-if="text.length > 0">{{ text }}</span>
  </div>
</template>

<style scoped lang="scss">
.gkl1 {
  padding: 3px;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.2s;
}

.gkl1:hover {
  opacity: 0.8;
}
</style>
