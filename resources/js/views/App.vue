<script>
import { defineComponent } from 'vue'
import NotificationComponent from '@/components/NotificationComponent.vue'

export default defineComponent({
  name: 'App',
  components: { NotificationComponent }
})
</script>

<template>
  <keep-alive>
    <div class="container">
      <NotificationComponent/>
      <router-view />
    </div>
  </keep-alive>
</template>

<style lang="scss">
@use '../../css/inc/reset';
@use '../../css/inc/default';
</style>
