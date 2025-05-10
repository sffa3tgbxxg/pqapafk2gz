<script lang="ts">
import { defineComponent, ref } from 'vue'
import { useAuth } from '@/composables/AuthService'

export default defineComponent({
  name: 'LoginPage',
  setup() {
    const settings = useAuth()

    return {
      ...settings,
    }
  },
})
</script>

<template>
  <div class="container">
    <div class="form-box login">
      <div class="form">
        <h1>Вход в аккаунт</h1>
        <div class="input-box">
          <input v-model="form.login" autocomplete="off" type="text" placeholder="Логин" required />
          <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
          <input
            v-model="form.password"
            autocomplete="off"
            type="password"
            placeholder="Пароль"
            required
          />
          <i class="bx bxs-lock-alt"></i>
        </div>
        <button @click="login" type="submit" class="btn">Войти</button>
      </div>
    </div>

    <div class="toggle-box">
      <div class="toggle-panel toggle-left">
        <h1>Добро пожаловать</h1>
        <p>Нет аккаунта?</p>
        <button class="btn register-btn">Регистрация</button>
      </div>

      <div class="toggle-panel toggle-right">
        <h1>Welcome Back!</h1>
        <p>Already have an account?</p>
        <button class="btn login-btn">Войти</button>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use '../../../css/pages/auth';
</style>
