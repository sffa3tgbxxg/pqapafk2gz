<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PaginationComponent',
  props: {
    currentPage: {
      type: Number,
      default: 1,
    },
    lastPage: {
      type: Number,
      required: true,
    },
    maxOffset: {
      type: Number,
      default: 6,
    },
  },
})
</script>

<template>
  <div class="n381">
    <div class="n382">
      <div class="n383 n383-selected">1</div>
      <div class="n383">2</div>
    </div>
  </div>
</template>

<style scoped lang="scss"></style>
