<script setup>
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import { mdiInformation, mdiTableBorder } from '@mdi/js'
import BaseButton from '@/components/BaseButton.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import { servicesService } from '@/composables/ServicesService.js'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import TableServices from '@/components/TableServices.vue'
import FiltersServices from '@/views/Services/FiltersServices.vue'

const { list } = servicesService()
const router = useRouter()
const services = ref(null)
const changePage = async (page) => {
  services.value = await list(page)
}

const redirect = (invoice) => {
  router.push({ name: 'accountInvoice', params: { id: invoice.id } })
}

onMounted(async () => {
  services.value = await list()
})
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Ваши сервисы" main />
      <div style="max-width: 100%; height: 18px"></div>
      <FiltersServices />
      <div style="max-width: 100%; height: 18px"></div>
      <CardBox class="mb-6" has-table>
        <TableServices
          v-if="services"
          :items="services"
          @redirect="redirect"
          @next-page="changePage"
        />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<style scoped></style>
