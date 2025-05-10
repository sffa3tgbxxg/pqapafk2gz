<script lang="ts">
import { defineComponent } from 'vue'
import HeaderComponent from '@/components/HeaderComponent.vue'
import Sidebar from '@/components/Sidebar.vue'

export default defineComponent({
  name: 'UserTemplate',
  components: { Sidebar, HeaderComponent },
})
</script>

<template>
  <Sidebar />
  <div class="zk21">
    <HeaderComponent />
    <slot />
  </div>
</template>

<style scoped lang="scss"></style>
