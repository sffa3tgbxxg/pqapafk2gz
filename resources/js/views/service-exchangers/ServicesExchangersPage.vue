<script lang="ts">
import { defineComponent, onMounted, ref, watch } from 'vue'
import UserTemplate from '@/views/UserTemplate.vue'
import HeaderPage from '@/components/HeaderPage.vue'
import { PlusIcon } from '@heroicons/vue/24/solid'
import FiltersComponent from '@/components/FiltersComponent.vue'
import InputComponent from '@/components/InputComponent.vue'
import SwitchComponent from '@/components/SwitchComponent.vue'
import IconInBox from '@/components/IconInBox.vue'
import { PencilIcon } from '@heroicons/vue/24/outline'
import PaginationComponent from '@/components/PaginationComponent.vue'
import ModalComponent from '@/components/ModalComponent.vue'
import { useServiceExchangers } from '@/composables/ServiceExchangersService'
import InputSearchComponent from '@/components/InputSearchComponent.vue'
import { useServices } from '@/composables/Services'
import { useExchangers } from '@/composables/Exchangers'

export default defineComponent({
  name: 'ServicesExchangersPage',
  methods: { PencilIcon },
  components: {
    InputSearchComponent,
    ModalComponent,
    PaginationComponent,
    IconInBox,
    SwitchComponent,
    InputComponent,
    FiltersComponent,
    HeaderPage,
    UserTemplate,
    PlusIcon,
  },
  setup() {
    const settings = useServiceExchangers()
    const services = ref(null)
    const exchangers = ref(null)
    const serviceExchangers = ref(null)

    onMounted(async () => {
      await search()
      services.value = (await useServices().getServices(false)).data
    })

    const setService = async (serviceId) => {
      if (settings.formCreate.value.service_id != parseInt(serviceId)) {
        settings.formCreate.value.service_id = parseInt(serviceId)
        exchangers.value = (await useExchangers().getExchangersByService(serviceId)).data
      }
    }

    const search = async () => {
      serviceExchangers.value = (await settings.getServiceExchangers()).data
    }

    return {
      ...settings,
      services,
      exchangers,
      serviceExchangers,
      setService,
      search,
    }
  },
})
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Платежные системы" undertitle="Управляйте платежными системами">
      <button
        @click="showCreateExchangerService = !showCreateExchangerService"
        class="btn btn-blue"
      >
        <PlusIcon style="width: 20px; height: 20px" />
        <span>Добавить платежную систему</span>
      </button>
    </HeaderPage>
    <FiltersComponent>
      <div class="zkl31">
        <InputComponent v-model="formSearch.payment_method" placeholder="Платежная система" />
        <InputComponent v-model="formSearch.service" placeholder="Сервис" />
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
          <th>Сервис</th>
          <th>Платежная система</th>
          <th>Общая комиссия</th>
          <th>Оборот</th>
          <th>Баланс</th>
          <th>Состояние</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="serviceExchanger in serviceExchangers" :key="serviceExchanger.id">
          <tr>
            <td>{{ serviceExchanger.id }}</td>
            <td>{{ serviceExchanger.service_name }}</td>
            <td>{{ serviceExchanger.exchanger_name }}</td>
            <td>{{ serviceExchanger.fee.total }} %</td>
            <td>{{ serviceExchanger.turnover }}</td>
            <td>{{ serviceExchanger.balance }} RUB</td>
            <td>{{ serviceExchanger.status }}</td>
            <td>
              <div class="zkl2n1">
                <IconInBox
                  title="Редактировать"
                  @click="editExchangerService(serviceExchanger)"
                  :icon="PencilIcon"
                />
                <SwitchComponent
                  @handle="
                    updateExchangerService({
                      id: serviceExchanger.id,
                      active: serviceExchanger.active,
                    })
                  "
                  v-model="serviceExchanger.active"
                />
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <ModalComponent
      v-if="showCreateExchangerService"
      @close="showCreateExchangerService = !showCreateExchangerService"
      @handle="createExchangerService"
      title="Добавление платежной системы"
    >
      <InputSearchComponent @select="setService" placeholder="Выберите сервис*">
        <template v-for="service in services" :key="service.id">
          <li :value="service.id">
            {{ service?.name }}
          </li>
        </template>
      </InputSearchComponent>
      <InputSearchComponent
        style="margin-top: 20px"
        @select="(value) => (formCreate.exchanger_id = parseInt(value))"
        placeholder="Выберите обменник*"
      >
        <template v-for="exchanger in exchangers" :key="exchanger.id">
          <li :value="exchanger.id">
            {{ exchanger?.name }}
          </li>
        </template>
      </InputSearchComponent>

      <InputComponent style="margin-top: 20px" v-model="formCreate.fee" placeholder="Ваша комиссия*" />

      <InputComponent
        style="margin-top: 20px"
        v-model="formCreate.api_key"
        placeholder="API Ключ*"
      />

      <InputComponent
        style="margin-top: 20px"
        v-model="formCreate.secret_key"
        placeholder="Secret Ключ"
      />
    </ModalComponent>

    <ModalComponent
      v-if="showEditExchangerService"
      @close="showEditExchangerService = !showEditExchangerService"
      @handle="updateExchangerService(formEdit)"
      title="Редактирование платежной системы"
    >
      <InputComponent style="margin-top: 20px" v-model="formEdit.fee" placeholder="Ваша комиссия" />
      <InputComponent style="margin-top: 20px" v-model="formEdit.api_key" placeholder="API Ключ" />
      <InputComponent
        style="margin-top: 20px"
        v-model="formEdit.secret_key"
        placeholder="Secret Ключ"
      />

      <div style="margin-top: 20px" class="addon-info-modal">
        <div class="gdk34n">Название</div>
        <div class="gdk33n">{{ formEditExchanger.name }}</div>
        <div class="gdk34n">Комиссия обменника</div>
        <div class="gdk33n">{{ formEditExchanger.fee }} %</div>
        <div class="gdk34n">Общая комиссия</div>
        <div class="gdk33n">
          {{
            parseFloat(formEditExchanger.fee) +
            parseFloat(formEdit.fee != null && formEdit.fee > 0 ? formEdit.fee : 0)
          }}
          %
        </div>
      </div>
    </ModalComponent>
  </UserTemplate>
</template>

<style scoped lang="scss">
@use '../../../css/pages/services';
</style>
