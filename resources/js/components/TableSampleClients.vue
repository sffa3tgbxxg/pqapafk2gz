<script lang="ts">
import { defineComponent } from 'vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import { mdiEye, mdiTrashCan } from '@mdi/js/commonjs/mdi'
import BaseLevel from '@/components/BaseLevel.vue'

export default defineComponent({
  name: 'TableSampleClients',
  methods: {
    mdiEye() {
      return mdiEye
    },
    mdiTrashCan() {
      return mdiTrashCan
    },
  },
  emits: ['next-page', 'redirect'],
  components: { BaseLevel, BaseButton, BaseButtons },
  props: {
    items: {
      type: Object,
    },
  },
  setup() {},
})
</script>

<template>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Сумма</th>
        <th>Статус</th>
        <th>Дата</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="item in items.data" @click="$emit('redirect', item)" :key="item.id">
        <td data-label="ID">
          {{ item.id }}
        </td>
        <td data-label="Amount">{{ item.amount_rub }}&nbsp;₽</td>
        <td data-label="Status">
          {{ item.status }}
        </td>
        <td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small class="text-gray-500 dark:text-slate-400" :title="item.created_at">{{
            item.created_at
          }}</small>
        </td>
      </tr>
    </tbody>
  </table>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <BaseLevel>
      <BaseButtons>
        <BaseButton
          v-for="page in items.meta.last_page"
          :key="page"
          :active="page === items.meta.current_page"
          :label="page"
          :color="page === items.meta.current_page ? 'lightDark' : 'whiteDark'"
          small
          @click="$emit('next-page', page)"
        />
      </BaseButtons>
      <small>Page {{ items.meta.current_page }} of {{ items.meta.last_page }}</small>
    </BaseLevel>
  </div>
</template>
