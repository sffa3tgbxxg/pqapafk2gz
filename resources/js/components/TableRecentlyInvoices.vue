<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'TableDefault',
})
</script>

<template>
  <div class="lzo3n1">
    <div class="lzo3n2">
      <div style="margin-bottom: 24px">
        <h2 class="zl6n">Последние заявки</h2>
      </div>
      <table class="table-1">
        <thead class="row header">
          <tr>
            <th class="cell">Метод</th>
            <th class="cell">Сумма</th>
            <th class="cell">Дата</th>
            <th class="cell">Статус</th>
          </tr>
        </thead>
        <tbody class="row">
          <tr>
            <td class="cell">10000</td>
            <td class="cell">Greengo</td>
            <td class="cell">27.04.2025 15:15</td>
            <td class="cell">
              <div style="display: flex">
                <div class="status-green">
                  <span>Оплачен</span>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use '../../css/inc/tables';
</style>
