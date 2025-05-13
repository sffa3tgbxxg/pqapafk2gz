<script lang="ts">
import { computed, defineComponent } from "vue";
import { useMenuStore } from "@/store/Menu";
import { storeToRefs } from "pinia";
import Icons from "@/composables/Icons";
import { useRoute } from "vue-router";
import { ChevronLeftIcon } from "@heroicons/vue/16/solid";
import SidebarMenu from "@/components/SidebarMenu.vue";

export default defineComponent({
  name: "Sidebar",
  components: { SidebarMenu, ChevronLeftIcon },
  setup() {
    const route = useRoute();
    const menuStore = useMenuStore();
    const { getMenu } = storeToRefs(menuStore); // теперь getMenu — это реактивное значение

    const menu = computed(() => {
      const items = [];
      for (const key in getMenu.value) {
        const item = getMenu.value[key];
        const pagesObj = item?.pages;
        let pagesObjIns = [];
        if (pagesObj) {
          for (const keyPage in pagesObj) {
            const itemPage = pagesObj[keyPage];
            pagesObjIns.push({
              active: keyPage == route.name,
              title: itemPage.name,
              to: { name: keyPage },
              icon: Icons[keyPage],
            });
          }
        }

        items.push({
          active: key == route.name,
          title: item.name,
          ...(pagesObjIns.length === 0 ? { to: { name: key } } : {}),
          pages: pagesObjIns,
          icon: Icons[key],
        });
      }
      return items;
    });

    return {
      menu,
    };
  },
});
</script>

<template>
  <nav class="navbar">
    <div class="li3n">
      <a class="ghl1"> </a>
    </div>

    <div class="klzk1">
      <div class="klzk2">
        <div class="">
          <ul class="klzk3">
            <template v-for="itemMenu in menu">
              <SidebarMenu :itemMenu="itemMenu" />
            </template>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped lang="scss"></style>
