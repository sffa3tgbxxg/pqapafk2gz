<script lang="ts">
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'SwitchComponent',
  props: {
    modelValue: {
      type: String,
      required: true,
    },
  },
  emits: ['update:modelValue', 'handle'],
  setup(_, { emit }) {
    const onSwitch = (event) => {
      const target = event.target as HTMLInputElement
      emit('update:modelValue', target.checked)
      emit('handle')
    }

    return {
      onSwitch,
    }
  },
})
</script>

<template>
  <label class="switch">
    <input type="checkbox" @change="onSwitch" v-model="modelValue" />
    <span class="slider"></span>
  </label>
</template>

<style scoped>
.switch {
  position: relative;
  display: inline-block;
  width: 25px;
  height: 12px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 12px;
}

.slider:before {
  position: absolute;
  content: '';
  height: 9px;
  width: 9px;
  left: 1.5px;
  bottom: 1.5px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #1faf63;
}

input:checked + .slider:before {
  transform: translateX(13px);
}
</style>
