<script lang="ts">
import { defineComponent, ref, watch } from 'vue'
import { format } from 'date-fns'

export default defineComponent({
  name: 'DatePicker',
  props: {
    placeholder: {
      type: String,
    },
    modelValue: {
      type: String,
      required: true,
    },
    format: {
      type: String,
      default: 'dd.MM.yyyy',
    },
    locale: {
      type: String,
      default: 'ru',
    },
    width: {
      type: Number,
      default: 200,
    },
    height: {
      type: Number,
      default: 42,
    },
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const model = ref(props.modelValue)

    watch(model, (newValue) => {
      if (newValue) {
        // Форматируем даты в dd.MM.yyyy
        const formatted = Array.isArray(newValue)
          ? newValue.map((date) => format(date, 'dd.MM.yyyy'))
          : format(newValue, 'dd.MM.yyyy')
        emit('update:modelValue', formatted)
      } else {
        emit('update:modelValue', null)
      }
    })

    return {
      model,
    }
  },
})
</script>

<template>
  <VueDatePicker
    :locale="locale"
    :placeholder="placeholder"
    :format="format"
    v-model="model"
    cancelText="Закрыть"
    selectText="Выбрать"
    :style="{ height: `${height}px`, width: `${width}px` }"
  />
</template>
