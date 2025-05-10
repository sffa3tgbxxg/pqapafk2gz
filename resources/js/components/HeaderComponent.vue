<script lang="ts">
import { defineComponent } from 'vue'
import { PowerIcon } from '@heroicons/vue/24/outline'

export default defineComponent({
  name: 'HeaderComponent',
  components: { PowerIcon },
})
</script>

<template>
  <header class="header">
    <div class="zl1">
      <div class="zl2">
        <div class="zl3">
          <span class="grey-color">Логин:&nbsp;</span>
          <span class="blue-color">Admin</span>
        </div>
        <div class="zl3">
          <span class="grey-color">Общий баланс:&nbsp;</span>
          <span class="blue-color">50000 RUB</span>
        </div>
        <div class="zl3">
          <PowerIcon
            class="blue-color"
            style="cursor: pointer; width: 22px; height: 22px; margin-right: 8px"
          />
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped lang="scss"></style>
