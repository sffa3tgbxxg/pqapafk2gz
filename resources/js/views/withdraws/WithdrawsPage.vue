<script lang="ts">
import { defineComponent } from 'vue'
import UserTemplate from '@/views/UserTemplate.vue'
import HeaderPage from '@/components/HeaderPage.vue'
import FiltersComponent from '@/components/FiltersComponent.vue'

export default defineComponent({
  name: 'WithdrawsPage',
  components: { FiltersComponent, HeaderPage, UserTemplate },
})
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Выводы" undertitle="Управляйте выводами" />
    <FiltersComponent> </FiltersComponent>
  </UserTemplate>
</template>

<style scoped lang="scss"></style>
