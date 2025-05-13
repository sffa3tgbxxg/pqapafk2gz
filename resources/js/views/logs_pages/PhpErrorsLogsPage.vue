<script lang="ts">
import { defineComponent, ref } from "vue";
import UserTemplate from "@/views/UserTemplate.vue";
import HeaderPage from "@/components/HeaderPage.vue";
import FiltersComponent from "@/components/FiltersComponent.vue";
import DatePicker from "@/components/DatePicker.vue";
import { usePhpLogsService } from "@/composables/PhpLogsService";
import IconInBox from "@/components/IconInBox.vue";
import PhpErrorLogInline from "@/views/logs_pages/PhpErrorLogInline.vue";
import PaginationComponent from "@/components/PaginationComponent.vue";

export default defineComponent({
  name: "PhpErrorsLogsPage",
  components: {
    PaginationComponent,
    PhpErrorLogInline,
    IconInBox,
    DatePicker,
    FiltersComponent,
    HeaderPage,
    UserTemplate,
  },
  setup() {
    const settings = usePhpLogsService();
    const logs = ref(null);
    const logsMeta = ref(null);

    const getLog = async () => {
      const logData = await settings.getLogs();
      logs.value = logData.data;
      logsMeta.value = logData.meta;
    };

    return {
      ...settings,
      getLog,
      logs,
      logsMeta,
    };
  },
});
</script>

<template>
  <UserTemplate>
    <HeaderPage title="Логи" undertitle="Следите за логами в PHP приложении" />

    <FiltersComponent>
      <div class="zkl31">
        <DatePicker
          :width="220"
          format="dd.MM.yyyy HH:mm:ss"
          placeholder="Дата от *"
          v-model="formSearch.from"
        />
        <DatePicker
          :width="220"
          format="dd.MM.yyyy HH:mm:ss"
          placeholder="Дата до *"
          v-model="formSearch.to"
        />
      </div>
      <div class="lg3n">
        <button @click="getLog" class="btn btn-blue">Поиск</button>
      </div>
    </FiltersComponent>
    <div style="max-width: 100%; background: transparent; height: 8px"></div>
    <div style="max-width: 100%; background: transparent; height: 8px"></div>
    <table class="table-1">
      <thead>
        <tr>
          <th>Дата</th>
          <th>Сообщение</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(log, key) in logs" :key="key">
          <PhpErrorLogInline :log="log" />
        </template>
      </tbody>
    </table>
    <div class="mw-100" style="height: 24px"></div>

    <PaginationComponent
      v-if="(logsMeta?.last_page ?? 1) > 1"
      :lastPage="logsMeta?.last_page ?? 1"
      :currentPage="logsMeta?.current_page ?? 1"
      @handle="
        (val) => {
          formSearch.page = val;
          getLog();
        }
      "
    />
  </UserTemplate>
</template>

<style scoped lang="scss"></style>
