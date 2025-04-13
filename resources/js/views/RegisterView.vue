<script setup>
import { reactive } from 'vue'
import { mdiAccount, mdiAsterisk } from '@mdi/js'
import SectionFullScreen from '@/components/SectionFullScreen.vue'
import CardBox from '@/components/CardBox.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import LayoutGuest from '@/layouts/LayoutGuest.vue'
import { authService } from '@/composables/AuthService.js'

const form = reactive({
  login: '',
  password: '',
  password_confirmation: '',
})

const auth = authService()

const submit = () => {
  auth.auth(form, 'register')
}
</script>

<template>
  <LayoutGuest>
    <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
      <CardBox :class="cardClass" is-form @submit.prevent="submit">
        <FormField label="Логин" help="Введите свой логин">
          <FormControl
            v-model="form.login"
            :icon="mdiAccount"
            name="login"
            placeholder="Логин"
            autocomplete="off"
          />
        </FormField>

        <FormField label="Пароль" help="Введите свой пароль">
          <FormControl
            v-model="form.password"
            :icon="mdiAsterisk"
            type="password"
            name="password"
            placeholder="Пароль"
            autocomplete="off"
          />
        </FormField>

        <FormField label="Повторите пароль" help="Повторите пароль">
          <FormControl
            v-model="form.password_confirmation"
            :icon="mdiAsterisk"
            type="password"
            name="password_confirmation"
            placeholder="Повторите пароль"
            autocomplete="off"
          />
        </FormField>

        <template #footer>
          <BaseButtons>
            <BaseButton type="submit" color="info" label="Зарегистрироваться" />
            <BaseButton to="/login" color="info" outline label="Вход" />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
