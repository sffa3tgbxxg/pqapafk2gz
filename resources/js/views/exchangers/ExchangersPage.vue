<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
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
import { useServices } from '@/composables/Services'
import { useExchangers } from '@/composables/Exchangers'

export default defineComponent({
  name: 'ExchangersPage',
  methods: { PencilIcon },
  components: {
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
    const exchangers = ref(null)
    const settings = useExchangers()

    onMounted(async () => {
      exchangers.value = (await settings.getExchangers()).data
    })

    return {
      ...settings,
      exchangers,
    }
  },
})
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Обменники" undertitle="Управляйте обменниками">
      <button @click="showCreateModal = !showCreateModal" class="btn btn-blue">
        <PlusIcon style="width: 20px; height: 20px" />
        <span>Добавить обменник</span>
      </button>
    </HeaderPage>
    <FiltersComponent>
      <div class="zkl31">
        <InputComponent v-model="formSearch.name" placeholder="Название" />
      </div>
      <div class="lg3n">
        <button class="btn btn-blue">Поиск</button>
      </div>
    </FiltersComponent>
    <div style="max-width: 100%; background: transparent; height: 8px"></div>
    <table class="table-1">
      <thead>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Состояние</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="exchanger in exchangers" :key="exchanger.id">
          <tr>
            <td>{{ exchanger.id }}</td>
            <td>{{ exchanger.name }}</td>
            <td>{{ exchanger.status }}</td>
            <td>
              <div class="zkl2n1">
                <IconInBox title="Редактировать" @click="edit(exchanger)" :icon="PencilIcon" />
                <SwitchComponent
                  @handle="update({ id: exchanger.id, active: exchanger.active })"
                  v-model="exchanger.active"
                />
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <ModalComponent
      v-if="showCreateModal"
      @close="showCreateModal = !showCreateModal"
      @handle="create"
      title="Создание обменника"
    >
      <InputComponent style="margin-top: 20px" v-model="formCreate.name" placeholder="Название" />
      <InputComponent style="margin-top: 20px" v-model="formCreate.fee" placeholder="Комиссия" />
      <InputComponent
        style="margin-top: 20px"
        v-model="formCreate.endpoint"
        placeholder="Endpoint API"
      />
      <InputComponent
        style="margin-top: 20px"
        v-model="formCreate.min_amount"
        placeholder="Минимальный сумма счета"
      />
      <InputComponent
        style="margin-top: 20px"
        v-model="formCreate.max_amount"
        placeholder="Максимальная сумма счета"
      />
      <InputComponent
        style="margin-top: 20px"
        v-model="formCreate.min_withdraw"
        placeholder="Минимальный вывод"
      />
      <div
        style="
          margin-top: 20px;
          align-items: center;
          justify-content: space-between;
          display: flex;
          text-align: left;
        "
      >
        <span>Автовывод: </span>
        <SwitchComponent v-model="formCreate.auto_withdraw" placeholder="Автовывод" />
      </div>
    </ModalComponent>

    <ModalComponent
      v-if="showEditModal"
      @close="showEditModal = !showEditModal"
      @handle="update(formEdit, true)"
      title="Редактирование обменника"
    >
      <InputComponent style="margin-top: 20px" v-model="formEdit.name" placeholder="Название" />
      <InputComponent
        style="margin-top: 20px"
        v-model="formEdit.endpoint"
        placeholder="Endpoint API"
      />
      <InputComponent style="margin-top: 20px" v-model="formEdit.fee" placeholder="Комиссия" />
      <InputComponent
        style="margin-top: 20px"
        v-model="formEdit.min_amount"
        placeholder="Минимальная сумма счета"
      />
      <InputComponent
        style="margin-top: 20px"
        v-model="formEdit.max_amount"
        placeholder="Максимальная сумма счета"
      />
      <div
        style="
          margin-top: 20px;
          align-items: center;
          justify-content: space-between;
          display: flex;
          text-align: left;
        "
      >
        <span>Автовывод: </span>
        <SwitchComponent v-model="formEdit.auto_withdraw" placeholder="Автовывод" />
      </div>
    </ModalComponent>
  </UserTemplate>
</template>

<style scoped lang="scss">
@use '../../../css/pages/services';
</style>
