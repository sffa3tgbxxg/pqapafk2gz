<script lang="ts">
import { defineComponent, onMounted, ref, watch } from 'vue'
import { useSubscriptionService } from '@/composables/SubscriptionsService'
import UserTemplate from '@/views/UserTemplate.vue'
import HeaderPage from '@/components/HeaderPage.vue'
import IconInBox from '@/components/IconInBox.vue'
import ModalComponent from '@/components/ModalComponent.vue'
import LoaderComponent from '@/components/LoaderComponent.vue'
import { useTimer } from '@/composables/Timer'

export default defineComponent({
  name: 'SubscriptionPage',
  components: { LoaderComponent, ModalComponent, IconInBox, HeaderPage, UserTemplate },
  setup() {
    const settings = useSubscriptionService()
    const invoices = ref(null)
    const invoiceForPayment = ref(null)
    const qrCode = ref(null)
    const timer = ref('')

    const targetDate = ref(null) // Реактивная переменная для targetDate
    const { formattedTimeLeft } = useTimer(targetDate) // Вызываем useTimer в setup

    onMounted(async () => {
      invoices.value = await settings.getInvoices()
    })
    const getInvoice = async () => {
      settings.showModalPayment.value = true
      invoiceForPayment.value = (await settings.generateInvoice())?.data
      qrCode.value = await settings.generateQr(invoiceForPayment.value.requisites.trim())
      targetDate.value = invoiceForPayment.value.expiry_at // Обновляем targetDate
      timer.value = formattedTimeLeft.value
    }

    const showInvoice = async (invoiceId) => {
      settings.showModalPayment.value = true
      invoiceForPayment.value = (await settings.showInvoice(invoiceId))?.data
      qrCode.value = await settings.generateQr(invoiceForPayment.value.requisites.trim())
      targetDate.value = invoiceForPayment.value.expiry_at // Обновляем targetDate
      timer.value = formattedTimeLeft.value
    }
    // Следим за изменениями formattedTimeLeft
    watch(formattedTimeLeft, (newValue) => {
      timer.value = newValue;
    });


    return {
      ...settings,
      invoices,
      invoiceForPayment,
      qrCode,
      timer,
      getInvoice,
      showInvoice,
    }
  },
})
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Подписка" undertitle="Подписка на сервис">
      <button @click="getInvoice()" class="btn btn-blue">
        <PlusIcon style="width: 20px; height: 20px" />
        <span>Оплатить подписку</span>
      </button>
    </HeaderPage>
    <div style="max-width: 100%; background: transparent; height: 8px"></div>
    <table class="table-1">
      <thead>
        <tr>
          <th>ID</th>
          <th>Дата</th>
          <th>Статус</th>
          <th>Сумма</th>
          <th>Валюта</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="invoice in invoices?.data" :key="invoice.id">
          <tr>
            <td>{{ invoice.id }}</td>
            <td>{{ invoice.created_at }}</td>
            <td>{{ invoice.status.name }}</td>
            <td>{{ invoice.amount }}</td>
            <td>{{ invoice.currency.code }}</td>
            <td>
              <div class="zkl2n1">
                <IconInBox
                  @click="showInvoice(invoice.id)"
                  v-if="invoice.status.code == 'pending'"
                  text="Посмотреть"
                />
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <ModalComponent
      v-if="showModalPayment"
      @close="((showModalPayment = !showModalPayment), (qrCode = null))"
      title="Оплата счета"
    >
      <LoaderComponent v-if="isLoadingPayment || !qrCode" />
      <template v-else>
        <div style="display: flex; align-items: center; justify-content: center">
          <div v-html="qrCode"></div>
        </div>

        <div style="margin-top: 20px" class="addon-info-modal">
          <div class="gdk34n">Сумма</div>
          <div style="cursor: pointer" @click="copyText(invoiceForPayment.amount)" class="gdk33n">
            {{ invoiceForPayment.amount }}
          </div>
          <div class="gdk34n">Валюта</div>
          <div class="gdk33n">{{ invoiceForPayment.currency.code }}</div>
          <div class="gdk34n" style="height: 40px">Реквизиты</div>
          <div
            @click="copyText(invoiceForPayment.requisites)"
            class="gdk33n"
            style="
              cursor: pointer;
              max-width: 280px;
              height: 40px;
              white-space: normal;
              text-wrap: wrap;
              overflow-y: auto;
            "
          >
            {{ invoiceForPayment.requisites }}
          </div>
          <div class="gdk34n">Осталось времени:</div>
          <div class="gdk33n">{{ timer }}</div>
        </div>
      </template>
    </ModalComponent>
  </UserTemplate>
</template>
