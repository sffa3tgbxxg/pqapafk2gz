<script lang="ts">
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'TextAreaComponent',
  props: {
    placeholder: {
      type: String,
      required: true,
    },
    modelValue: {
      type: String,
      required: true,
    },
    type:{
      type: String,
      required: false,
      default: "search",
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const onInput = (event) => {
      const target = event.target as HTMLInputElement
      emit('update:modelValue', target.value)
    }
    const isFocused = ref(false)

    const focused = () => {
      isFocused.value = true
    }

    const blured = () => {
      isFocused.value = false
    }

    return {
      blured,
      focused,
      onInput,
      isFocused,
    }
  },
})
</script>

<template>
  <div :class="['block-input-1','textarea-1', { 'active-block-input-1': isFocused || modelValue?.length > 0 }]">
    <textarea
      @focus="focused"
      @blur="blured"
      :value="modelValue"
      @input="onInput"
      :type="type"
      class="input-1"
    />
    <span :class="['placeholder-input-1', { 'active-placeholder-input-1': modelValue?.length > 0 || isFocused}]">
      {{ placeholder }}
    </span>


  </div>
</template>

<style scoped lang="scss"></style>
