<script lang="ts ">
import { defineComponent, onMounted, ref } from 'vue'
import DatePicker from '@/components/DatePicker.vue'
import InputSearchComponent from '@/components/InputSearchComponent.vue'
import InputComponent from '@/components/InputComponent.vue'
import PaginationComponent from '@/components/PaginationComponent.vue'
import FiltersComponent from '@/components/FiltersComponent.vue'
import IconInBox from '@/components/IconInBox.vue'
import HeaderPage from '@/components/HeaderPage.vue'
import UserTemplate from '@/views/UserTemplate.vue'
import { useStatsExchangers } from '@/composables/StatsExchangers'
import { useServices } from '@/composables/Services'
import { useInvoices } from '@/composables/InvoicesService'

export default defineComponent({
  name: 'ExchangersStatistics',
  components: {
    UserTemplate,
    HeaderPage,
    IconInBox,
    FiltersComponent,
    PaginationComponent,
    InputComponent,
    InputSearchComponent,
    DatePicker
  },
  setup() {
    const settings = useStatsExchangers()
    const statuses = ref(null)
    const services = ref(null)
    const data = ref(null)

    onMounted(async () => {
      services.value = (await useServices().getServices(false)).data
      statuses.value = (await useInvoices().getStatuses())
    })

    return {
      ...settings,
      services,
      data,
      statuses
    }
  }
})
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Статистика" undertitle="Статистика обменников" />
    <FiltersComponent>
      <div class="zkl31">
        <DatePicker placeholder="Дата от *" v-model="formSearch.from" />
        <DatePicker placeholder="Дата до *" v-model="formSearch.to" />
        <InputSearchComponent
          @select="(value) => (formSearch.service_id = value)"
          placeholder="Выбрать сервис*"
        >
          <template v-for="service in services">
            <li :value="service?.id">{{ service.name }}</li>
          </template>
        </InputSearchComponent>
        <InputSearchComponent @select="(value) => (formSearch.status = value)" placeholder="Статус">
          <template v-for="statusSearch in statuses">
            <li :value="null">Любой</li>
            <li :value="statusSearch.code">{{ statusSearch.name }}</li>
          </template>
        </InputSearchComponent>
        <InputSearchComponent placeholder="Выбрать платежную систему"></InputSearchComponent>

        <InputComponent v-model="formSearch.user" placeholder="ID Счета" />
        <InputComponent v-model="formSearch.user" placeholder="ID внешней транзакции" />
        <InputComponent v-model="formSearch.user" placeholder="ID или Никнейм пользователя" />
      </div>
      <div class="lg3n">
        <button @click="search" class="btn btn-blue">Поиск</button>
      </div>
    </FiltersComponent>
    <div style="max-width: 100%; background: transparent; height: 8px"></div>
    <table class="table-1">
      <thead>
        <tr>
          <th>ID</th>
          <th>Дата</th>
          <th>Сервис</th>
          <th>ID внешней операции</th>
          <th>Платежная система</th>
          <th>Статус</th>
          <th>Пользователь</th>
          <th>Сумма</th>
          <th>Валюта</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="invoice in data" :key="invoice.id">
          <tr>
            <td>{{ invoice.id }}</td>
            <td>{{ invoice.time.created_at_format }}</td>
            <td>{{ invoice.service?.name }}</td>
            <td>{{ invoice.external_id }}</td>
            <td>{{ invoice.payment_method?.name }}</td>
            <td>{{ invoice.status.name }}</td>
            <td>{{ invoice.user.nickname }}</td>
            <td>
              <div class="zk3n1x">
                <div class="zk3nz0">
                  <span class="zk3nz">С комиссией:</span>
                  <span>{{ invoice.amount.in }} </span>
                </div>
                <div class="zk3nz0">
                  <span class="zk3nz">Без комиссии:</span>
                  <span>{{ invoice.amount.out }} </span>
                </div>
              </div>
            </td>
            <td>{{ invoice.currency?.code }}</td>
            <td>
              <div class="zkl2n1">
                <IconInBox
                  v-if="['pending'].includes(invoice.status.code)"
                  @click="((showAcceptModal = !showAcceptModal), (targetInvoice = invoice))"
                  border-color="rgb(53 229 117)"
                  color="rgb(57 153 91)"
                  text="Подтвердить"
                />
                <IconInBox
                  @click="((showCancelModal = !showCancelModal), (targetInvoice = invoice))"
                  v-if="['pending'].includes(invoice.status.code)"
                  text="Отменить"
                />
                <IconInBox
                  @click="((showInfoModal = !showInfoModal), edit(invoice))"
                  v-if="!['pending', 'search'].includes(invoice.status.code)"
                  text="Изменить"
                />
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
    <div class="mw-100" style="height: 24px"></div>

    <!--    <PaginationComponent-->
    <!--      v-if="(invoicesData?.meta?.last_page ?? 1) > 1"-->
    <!--      :lastPage="invoicesData?.meta?.last_page ?? 1"-->
    <!--      :currentPage="invoicesData?.meta?.current_page ?? 1"-->
    <!--      @handle="-->
    <!--        (val) => {-->
    <!--          formSearch.page = val;-->
    <!--          search();-->
    <!--        }-->
    <!--      "-->
    <!--    />-->
  </UserTemplate>
</template>

<style scoped lang="scss"></style>
