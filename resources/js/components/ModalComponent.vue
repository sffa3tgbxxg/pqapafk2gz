<script lang="ts">
import { defineComponent } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

export default defineComponent({
  name: 'ModalComponent',
  components: { XMarkIcon },
  emits: ['close', 'handle'],
  props: {
    title: {
      type: String,
      default: 'Подтверждение',
    },
    handleButton: {
      type: Boolean,
      default: true,
    },
  },
})
</script>

<template>
  <div class="kgn1">
    <div class="kgn1o"></div>
    <div class="knz1">
      <div class="kg3z1">
        <div class="zkn41">
          <div class="glzn1">
            <div class="glzn2">
              <div class="glzn3">
                <div class="glzn4">
                  <div class="glzn5">{{ title }}</div>
                  <button @click="$emit('close')" class="glzn6">
                    <XMarkIcon style="width: 22px; height: 22px" />
                  </button>
                </div>
                <div class="glzn7">
                  <div class="glzn8">
                    <div class="glzn0">
                      <slot />
                    </div>
                    <div class="glzn9">
                      <button v-if="handleButton" @click="$emit('handle')" class="btn btn-blue">
                        <span>Подтвердить</span>
                      </button>
                      <button @click="$emit('close')" class="btn btn-white">
                        <span>Закрыть</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss"></style>
