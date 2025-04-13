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
})

const auth = authService()

const submit = () => {
  auth.auth(form, 'login')
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
            autocomplete="username"
          />
        </FormField>

        <FormField label="Пароль" help="Введите свой пароль">
          <FormControl
            v-model="form.password"
            :icon="mdiAsterisk"
            type="password"
            name="password"
            placeholder="Пароль"
            autocomplete="current-password"
          />
        </FormField>

        <template #footer>
          <BaseButtons>
            <BaseButton :disabled="auth.isLoading.value" type="submit" color="info" label="Войти" />
            <BaseButton to="/register" color="info" outline label="Регистрация" />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
