<script lang="ts">
import { defineComponent, onMounted, onUnmounted, ref } from 'vue'

export default defineComponent({
  name: 'InputSearchComponent',
  props: {
    placeholder: {
      type: String,
      default: '',
    },
    options: {
      type: Object,
    },
    selectedLabelProp: {
      type: String,
      default: '',
    },
  },
  emits: ['select', 'handle-click'],
  setup(props, { emit }) {
    const open = ref(false)
    const selectedLabel = ref(props.selectedLabelProp)
    const root = ref<HTMLElement | null>(null)
    const triggerRef = ref<HTMLElement | null>(null)
    const dropdownStyles = ref<Record<string, string>>({})

    const handleWrapperClick = (e: MouseEvent) => {
      const target = e.target as HTMLElement
      if (target.tagName.toLowerCase() === 'input') return
      e.stopPropagation()
      open.value = !open.value
      if (open.value) {
        updateDropdownPosition()
        emit('handle-click')
      }
    }

    const updateDropdownPosition = () => {
      if (root.value) {
        const rect = root.value.getBoundingClientRect()
        dropdownStyles.value = {
          position: 'fixed',
          top: `${rect.bottom + window.scrollY}px`,
          left: `${rect.left + window.scrollX}px`,
          minWidth: `${rect.width}px`,
          maxWith: '100%',
          zIndex: '9999',
          background: 'white',
          border: '1px solid #ccc',
        }
      }
    }

    const handleClickOutside = (e: MouseEvent) => {
      if (root.value && !root.value.contains(e.target as Node)) {
        open.value = false
      }
    }

    const handleItemClick = (e: MouseEvent) => {
      const target = e.target as HTMLElement
      if (target.tagName.toLowerCase() !== 'li') return

      const value = target.getAttribute('value') || ''
      selectedLabel.value = target.textContent?.trim() || ''
      emit('select', value)
      open.value = false
    }

    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
    })

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })

    return {
      open,
      selectedLabel,
      handleWrapperClick,
      handleItemClick,
      root,
      props,
      dropdownStyles,
    }
  },
})
</script>

<template>
  <div
    ref="root"
    @click="handleWrapperClick"
    :class="['block-input-1', 'search', { 'active-block-input-1': selectedLabel.length > 0 }]"
  >
    <span
      :class="[
        'placeholder-input-1',
        {
          'active-placeholder-input-1': selectedLabel.length > 0,
        },
      ]"
    >
      {{ placeholder }}
    </span>

    <span
      v-if="selectedLabel.length > 0"
      :class="['selected-label-input-1', { 'black-color': selectedLabel.length > 0 }]"
    >
      {{ selectedLabel }}
    </span>

    <div v-if="open" class="block-input-search-1" :style="dropdownStyles">
      <div class="zkl3n1">
        <input type="search" placeholder="Поиск" />
      </div>
      <ul @click.stop="handleItemClick">
        <slot />
      </ul>
    </div>
  </div>
</template>

<style scoped lang="scss"></style>
