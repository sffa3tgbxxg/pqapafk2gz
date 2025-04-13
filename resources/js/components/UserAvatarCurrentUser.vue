<script setup>
import UserAvatar from '@/components/UserAvatar.vue'
import { useUserStore } from '@/stores/user.js'

const userStore = useUserStore();
</script>

<template>
  <UserAvatar :username="userStore.user.login" :avatar="false">
    <slot />
  </UserAvatar>
</template>
