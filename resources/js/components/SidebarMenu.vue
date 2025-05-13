<script lang="ts">
import { ChevronLeftIcon } from "@heroicons/vue/16/solid/index.js";

import { defineComponent, ref } from "vue";
import { useRoute } from "vue-router";

export default defineComponent({
  name: "SidebarMenu",
  components: { ChevronLeftIcon },
  props: {
    itemMenu: {
      type: Object,
    },
  },
  setup(props) {
    const showAddonPages = ref(false);
    const route = useRoute();

    if (props.itemMenu.pages.length > 0) {
      showAddonPages.value = props.itemMenu.pages.some((val) => val.to.name == route.name);
    }

    return {
      showAddonPages,
      route,
    };
  },
});
</script>

<template>
  <li>
    <router-link
      v-if="itemMenu.pages.length <= 0"
      :class="[{ 'active-m21': itemMenu.active }]"
      :to="itemMenu?.to"
    >
      <component style="width: 16px; height: 16px" :is="itemMenu?.icon" />
      <span>{{ itemMenu.title }}</span>
    </router-link>
    <a v-else style="display: block">
      <div @click="showAddonPages = !showAddonPages" class="gk3nz">
        <component style="width: 16px; height: 16px" :is="itemMenu?.icon" />
        <span>{{ itemMenu.title }}</span>
        <ChevronLeftIcon
          :class="['gksn3zklI', { gfsdlg39: showAddonPages }]"
          style="width: 16px; height: 16px; margin-left: auto"
        />
      </div>
      <ul :class="['submenu', { 'submenu--open': showAddonPages }]">
        <template v-for="itemPage in itemMenu.pages">
          <router-link :to="itemPage.to" :class="[{ 'active-m21': itemPage.active }]">
            <component style="width: 16px; height: 16px" :is="itemPage?.icon" />
            <span>{{ itemPage.title }}</span>
          </router-link>
        </template>
      </ul>
    </a>
  </li>
</template>

<style scoped lang="scss"></style>
