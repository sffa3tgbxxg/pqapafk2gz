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

export default defineComponent({
  name: 'ServicesPage',
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
    const settings = useServices()
    const services = ref(null)

    onMounted(async () => {
      services.value = await settings.getServices()
    })

    return {
      ...settings,
      services,
    }
  },
})
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Сервисы" undertitle="Управляйте сервисами">
      <button @click="showCreateService = !showCreateService" class="btn btn-blue">
        <PlusIcon style="width: 20px; height: 20px" />
        <span>Добавить сервис</span>
      </button>
    </HeaderPage>
    <FiltersComponent>
      <div class="zkl31">
        <InputComponent type="number" v-model="formSearch.id" placeholder="ID" />
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
        <template v-for="service in services?.data" :key="service.id">
          <tr>
            <td>{{ service.id }}</td>
            <td>{{ service.name }}</td>
            <td>{{ service.status }}</td>
            <td>
              <div class="zkl2n1">
                <IconInBox title="Редактировать" @click="editService(service)" :icon="PencilIcon" />
                <SwitchComponent @handle="updateService(service)" v-model="service.active" />
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <ModalComponent
      v-if="showCreateService"
      @close="showCreateService = !showCreateService"
      @handle="createService"
      title="Создание сервиса"
    >
      <InputComponent v-model="formCreate.name" placeholder="Название" />
    </ModalComponent>

    <ModalComponent
      v-if="showEditService"
      @close="showEditService = !showEditService"
      @handle="updateService(formEdit)"
      title="Редактирование сервиса"
    >
      <InputComponent v-model="formEdit.name" placeholder="Название" />
    </ModalComponent>
  </UserTemplate>
</template>

<style scoped lang="scss">
@use '../../../css/pages/services';
</style>
