<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'HeaderPage',
  props: {
    title: {
      type: String,
      required: true,
    },
    undertitle: {
      type: String,
    },
  },
})
</script>

<template>
  <div class="zo23n1">
    <div class="zo23n2">
      <h3>{{title}}</h3>
      <span class="dark-grey-color">{{ undertitle}}</span>
    </div>
    <slot />
  </div>
</template>

<style scoped lang="scss"></style>
