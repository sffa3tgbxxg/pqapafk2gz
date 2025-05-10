<script lang="ts">
import { defineComponent } from 'vue'
import ItemBlockInfo from '@/views/home/ItemBlockInfo.vue'
import TableDefault from '@/components/TableRecentlyInvoices.vue'
import UserTemplate from '@/views/UserTemplate.vue'

export default defineComponent({
  name: 'HomePage',
  components: { UserTemplate, TableDefault, ItemBlockInfo },
})
</script>

<template>
  <UserTemplate>
    <div class="lz3n">
      <ItemBlockInfo />
      <ItemBlockInfo />
      <ItemBlockInfo />
      <ItemBlockInfo />
    </div>
    <div style="max-width: 100%; height: 44px"></div>
    <TableDefault />
  </UserTemplate>
</template>

<style scoped lang="scss">
@use '../../../css/pages/home';
</style>
