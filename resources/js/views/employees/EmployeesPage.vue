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
import { useEmployee } from '@/composables/EmployeeService'
import { TrashIcon } from '@heroicons/vue/16/solid'
import TextAreaComponent from '@/components/TextAreaComponent.vue'

export default defineComponent({
  name: 'EmployeesPage',
  methods: { TrashIcon, PencilIcon },
  components: {
    TextAreaComponent,
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
    const settings = useEmployee()
    const employees = ref(null)
    const services = ref(null)
    const rolesCreate = ref(null)

    onMounted(async () => {
      employees.value = (await settings.getEmployees()).data
      services.value = (await useServices().getServices(false)).data
    })

    const updateRolesByService = async (serviceId) => {
      rolesCreate.value = (await settings.updateRolesCreate(serviceId)).data
      settings.formCreate.value.service_id = serviceId
    }

    return {
      ...settings,
      employees,
      services,
      rolesCreate,
      updateRolesByService,
    }
  },
})
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Сотрудники" undertitle="Управляйте сотрудниками">
      <button @click="showCreateModal = !showCreateModal" class="btn btn-blue">
        <PlusIcon style="width: 20px; height: 20px" />
        <span>Добавить сотрудника</span>
      </button>
    </HeaderPage>
    <FiltersComponent>
      <div class="zkl31">
        <InputComponent v-model="formSearch.service_id" placeholder="Сервис" />
        <InputComponent v-model="formSearch.role" placeholder="Должность" />
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
          <th>Сервис</th>
          <th>Логин</th>
          <th>Должность</th>
          <th>Дата поступления</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="employee in employees" :key="employee.id">
          <tr>
            <td>{{ employee.id }}</td>
            <td>{{ employee.service_name }}</td>
            <td>{{ employee.login }}</td>
            <td>{{ employee.role }}</td>
            <td>{{ employee.created_at }}</td>
            <td>
              <div class="zkl2n1">
                <IconInBox
                  v-if="employee.accesses.can_edit_delete"
                  title="Редактировать"
                  @click="editEmployee(employee)"
                  :icon="PencilIcon"
                />
                <IconInBox
                  v-if="employee.accesses.can_edit_delete"
                  title="Уволить"
                  @click="deleteEmployee(employee,true)"
                  :icon="TrashIcon"
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
      @handle="createEmployee"
      title="Добавление нового сотрудника"
    >
      <InputComponent style="margin-top: 20px" v-model="formCreate.login" placeholder="Логин" />
      <InputSearchComponent
        style="margin-top: 20px"
        @select="updateRolesByService"
        placeholder="Выберите сервис"
      >
        <template v-for="service in services">
          <li :value="service.id">{{ service.name }}</li>
        </template>
      </InputSearchComponent>
      <InputSearchComponent
        style="margin-top: 20px"
        @select="(role) => (formCreate.role = role)"
        placeholder="Выберите должность"
      >
        <template v-for="roleCreate in rolesCreate">
          <li :value="roleCreate.name_code">{{ roleCreate.name }}</li>
        </template>
      </InputSearchComponent>
      <TextAreaComponent
        style="margin-top: 20px"
        v-model="formCreate.comment"
        placeholder="Комментарий"
      />
      <TextAreaComponent
        style="margin-top: 20px"
        v-model="formCreate.contacts"
        placeholder="Контакты"
      />
    </ModalComponent>

    <ModalComponent
      v-if="showEditModal"
      @close="showEditModal = !showEditModal"
      @handle="updateEmployee(formEdit)"
      title="Редактирование сотрудника"
    >
      <InputSearchComponent
        @select="(value) => (formEdit.role = value)"
        placeholder="Выберите должность"
        :selectedLabelProp="formEdit.role_name"
      >
        <template v-for="roleEdit in rolesEdit">
          <li :value="roleEdit.name_code">{{ roleEdit.name }}</li>
        </template>
      </InputSearchComponent>
      <InputComponent
        style="margin-top: 20px"
        v-model="formEdit.comment"
        placeholder="Комментарий"
      />
      <InputComponent style="margin-top: 20px" v-model="formEdit.contacts" placeholder="Контакты" />
    </ModalComponent>

    <ModalComponent @close="showDeleteModal = !showDeleteModal" @handle="deleteEmployee(formDelete, false)" v-if="showDeleteModal">
      <span>Вы уверены, что хотите удалить сотрудника <span class="blue-color">{{formDelete.login}}</span>?</span>
    </ModalComponent>
  </UserTemplate>
</template>

<style scoped lang="scss">
@use '../../../css/pages/services';
</style>
