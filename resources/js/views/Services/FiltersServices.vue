<script lang="ts">
import { defineComponent } from 'vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import { mdiAccount } from '@mdi/js'

export default defineComponent({
  methods: {
    mdiAccount() {
      return mdiAccount
    },
  },
  components: { FormControl, FormField },
})
</script>

<template>
  <div class="bg-white w-full dark\:bg-slate-900\/70">
    <div class="p-2 fields">
      <FormField label="ID">
        <FormControl
          :icon="mdiAccount()"
          name="login"
          placeholder="ID"
          autocomplete="username"
        />
      </FormField>


      <FormField label="Название">
        <FormControl
          :icon="mdiAccount()"
          name="login"
          placeholder="Название"
          autocomplete="username"
        />
      </FormField>

      <FormField label="Состояние">
        <FormControl
          :icon="mdiAccount()"
          name="login"
          placeholder="Состояние"
          autocomplete="username"
        />
      </FormField>





    </div>
  </div>
</template>

<style scoped>
.fields {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 10px;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
</style>
