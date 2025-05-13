<script lang="ts">
import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "ApiErrorLogInline",
  props: {
    log: {
      type: String,
    },
  },
  setup() {
    const showFull = ref(false);

    return {
      showFull,
    };
  },
});
</script>

<template>
  <tr>
    <td style="cursor: pointer" @click="showFull = !showFull">{{ log.created_at }}</td>
    <td>
      {{ log.exchanger_name }}
    </td>
    <td>
      {{ log.invoice_id }}
    </td>
    <td>
      <span :class="['zl3nz90', { 'zl3nz90-a': showFull }]">
        {{ log.message }}
      </span>
    </td>
  </tr>
</template>

<style scoped lang="scss"></style>
