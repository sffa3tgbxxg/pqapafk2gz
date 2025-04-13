<script setup>
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import { mdiInformation, mdiTableBorder } from '@mdi/js'
import BaseButton from '@/components/BaseButton.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import TableSampleClients from '@/components/TableSampleClients.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import { accountService } from '@/composables/AccountService.js'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const { generate, list } = accountService()
const invoices = ref(null)
const router = useRouter()

const changePage = async (page) => {
  invoices.value = await list(page)
}

const redirect = (invoice) => {
  router.push({ name: 'accountInvoice', params: { id: invoice.id } })
}

onMounted(async () => {
  invoices.value = await list()
})
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <NotificationBar color="info" :icon="mdiInformation" :outline="notificationsOutline">
        <b>Напоминание</b>. Чтобы пользоваться сервисом вам необходимо оплатить подписку
        <template #right>
          <BaseButton
            @click="generate"
            label="Оплатить"
            :color="notificationsOutline ? 'info' : 'white'"
            :outline="notificationsOutline"
            rounded-full
            small
          />
        </template>
      </NotificationBar>

      <SectionTitleLineWithButton :icon="mdiTableBorder" title="История подписок" main />

      <CardBox class="mb-6" has-table>
        <TableSampleClients
          v-if="invoices"
          :items="invoices"
          @redirect="redirect"
          @next-page="changePage"
        />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<style scoped></style>
