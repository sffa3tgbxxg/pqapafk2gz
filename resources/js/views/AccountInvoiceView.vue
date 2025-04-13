<script lang="ts">
import { computed, defineComponent, watchEffect } from 'vue'
import SectionFullScreen from '@/components/SectionFullScreen.vue'
import CardBox from '@/components/CardBox.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import { accountService } from '@/composables/AccountService.js'
import { mdiReload, mdiContentCopy } from '@mdi/js'
import CardBoxComponentTitle from '@/components/CardBoxComponentTitle.vue'
import { useRoute, useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'
import LayoutGuest from '@/layouts/LayoutGuest.vue'

export default defineComponent({
  name: 'AccountInvoice',
  components: {
    SectionFullScreen,
    CardBox,
    BaseButton,
    BaseButtons,
    CardBoxComponentTitle,
    LayoutGuest,
  },
  setup() {
    const route = useRoute()
    const router = useRouter()
    const invoiceId = route.params.id
    const account = accountService()
    const invoice = ref(null)

    onMounted(async () => {
      invoice.value = await account.get(invoiceId)
    })

    return {
      mdiReload,
      mdiContentCopy,
      invoice,
      router,
    }
  },
})
</script>

<template>
  <LayoutGuest>
    <SectionFullScreen bg="white">
      <div style="box-shadow: 0 0 7px 7px #e4e8f1" class="bg-white rounded-2xl">
        <div
          class="text-white rounded-t-lg p-3 bg-linear-to-tr bg-blue-400 via-blue-350 to-blue-400"
        >
          <h1 style="font-size: 32px" class="font-bold">Оплата счета</h1>

          <div class="mt-3">
            <div class="flex items-center">
              <span class="font-bold text-3xl mr-2">{{ invoice?.amount_btc }} BTC</span>
              <svg style="cursor: pointer" width="22" height="22" viewBox="0 0 24 24">
                <path :d="mdiContentCopy" fill="currentColor" />
              </svg>
            </div>
          </div>
        </div>

        <div
          style="height: 240px; margin: 15px 0 8px 0"
          class="relative flex justify-center items-center flex-col"
        >
          <div style="background: oklch(0.74 0.15 251.77)" class="h-full rounded-xl">
            <div class="h-full p-1.5">
              <img
                style="max-height: 100%; max-width: 100%"
                class="rounded-sm"
                src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.britannica.com%2F17%2F155017-050-9AC96FC8%2FExample-QR-code.jpg&f=1&nofb=1&ipt=398414c0e50798cb57bf7570932c200a259d189fe2352bbdd215cf23e479f159&ipo=images"
              />
            </div>
          </div>
        </div>

        <div class="text-center">
          <span style="font-size: 14px" class="text-stone-500">Отсканируйте QR-код для оплаты</span>
        </div>

        <div class="space-y-3 p-3">
          <div v-if="invoice" class="flex items-center">
            <div class="bg-white h-requisites rounded-sm">
              <span class="mr-3" style="font-size: 21px; font-weight: 500">{{
                invoice?.requisites
              }}</span>
            </div>
          </div>
        </div>

        <div class="p-3">
          <BaseButton
            label="Назад"
            @click="router.push({ name: 'account' })"
            style="width: 100%"
            color="info"
            outline
          />
        </div>
      </div>
    </SectionFullScreen>
  </LayoutGuest>
</template>
