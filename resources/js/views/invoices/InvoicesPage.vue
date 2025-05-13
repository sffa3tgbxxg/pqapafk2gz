<script lang="ts">
import { defineComponent, onMounted, Ref, ref } from "vue";
import UserTemplate from "@/views/UserTemplate.vue";
import HeaderPage from "@/components/HeaderPage.vue";
import FiltersComponent from "@/components/FiltersComponent.vue";
import InputComponent from "@/components/InputComponent.vue";
import { PencilIcon } from "@heroicons/vue/24/outline";
import PaginationComponent from "@/components/PaginationComponent.vue";
import ModalComponent from "@/components/ModalComponent.vue";
import { TrashIcon } from "@heroicons/vue/16/solid";
import { useInvoices } from "@/composables/InvoicesService";
import DatePicker from "@/components/DatePicker.vue";
import InputSearchComponent from "@/components/InputSearchComponent.vue";
import { useServices } from "@/composables/Services";
import IconInBox from "@/components/IconInBox.vue";
import TextAreaComponent from "@/components/TextAreaComponent.vue";

export default defineComponent({
  name: "EmployeesPage",
  methods: { TrashIcon, PencilIcon },
  components: {
    TextAreaComponent,
    IconInBox,
    InputComponent,
    InputSearchComponent,
    DatePicker,
    PaginationComponent,
    ModalComponent,
    FiltersComponent,
    HeaderPage,
    UserTemplate,
  },
  setup() {
    const invoices = ref(null);
    const services = ref(null);
    const settings = useInvoices();
    const invoicesData = ref(null);
    const statuses = ref(null);

    onMounted(async () => {
      services.value = (await useServices().getServices(false)).data;
      statuses.value = await settings.getStatuses();
    });

    const search = async () => {
      invoicesData.value = await settings.getInvoices();
      invoices.value = invoicesData.value.data;
    };

    return {
      ...settings,
      invoices,
      invoicesData,
      services,
      statuses,
      search,
    };
  },
});
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Счета" undertitle="Управляйте счетами" />
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
        <InputSearchComponent
          @select="(value) => (formSearch.problem = value)"
          placeholder="Проблемные"
        >
          <li :value="0">Любые</li>
          <li :value="1">Да</li>
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
        <template v-for="invoice in invoices" :key="invoice.id">
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

    <PaginationComponent
      v-if="(invoicesData?.meta?.last_page ?? 1) > 1"
      :lastPage="invoicesData?.meta?.last_page ?? 1"
      :currentPage="invoicesData?.meta?.current_page ?? 1"
      @handle="
        (val) => {
          formSearch.page = val;
          search();
        }
      "
    />

    <ModalComponent
      v-if="showCancelModal"
      @close="((showCancelModal = !showCancelModal), (commentForm.comment = null))"
      @handle="cancel(targetInvoice.id)"
      title="Отменить счет"
    >
      <TextAreaComponent v-model="commentForm.comment" placeholder="Комментарий*" />

      <div style="margin-top: 20px" class="addon-info-modal">
        <div class="gdk34n">Сервис</div>
        <div class="gdk33n">{{ targetInvoice.service.name }}</div>
        <div class="gdk34n">Сумма</div>
        <div class="gdk33n">{{ targetInvoice.amount.out }} RUB</div>
      </div>
    </ModalComponent>

    <ModalComponent
      v-if="showAcceptModal"
      @close="((showAcceptModal = !showAcceptModal), (commentForm.comment = null))"
      @handle="accept(targetInvoice.id)"
      title="Подтвердить счет"
    >
      <TextAreaComponent v-model="commentForm.comment" placeholder="Комментарий*" />

      <div style="margin-top: 20px" class="addon-info-modal">
        <div class="gdk34n">Сервис</div>
        <div class="gdk33n">{{ targetInvoice.service.name }}</div>
        <div class="gdk34n">Сумма</div>
        <div class="gdk33n">{{ targetInvoice.amount.out }} RUB</div>
      </div>
    </ModalComponent>
    <ModalComponent
      @handle="update"
      v-if="showInfoModal"
      @close="showInfoModal = !showInfoModal"
      :title="`Информация о счете #${editForm.id}`"
    >
      <div class="gkn312">
        <InputComponent :readonly="true" v-model="editForm.id" placeholder="ID" />
        <InputComponent :readonly="true" v-model="editForm.date" placeholder="Дата" />
        <InputComponent :readonly="true" v-model="editForm.amount" placeholder="Сумма" />
        <InputComponent :readonly="true" v-model="editForm.exchanger_name" placeholder="Обменник" />
        <InputSearchComponent
          @select="(value) => (editForm.status.code = value)"
          :selectedLabelProp="editForm.status.name"
          placeholder="Статус"
        >
          <template v-for="status in statuses">
            <li :value="status?.code">{{ status?.name }}</li>
          </template>
        </InputSearchComponent>
        <TextAreaComponent v-model="editForm.comment" placeholder="Комментарий" />
      </div>
    </ModalComponent>
  </UserTemplate>
</template>

<style scoped lang="scss">
@use "../../../css/pages/services";
</style>
