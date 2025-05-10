<script lang="ts">
import { computed, defineComponent } from 'vue'
import { useMenuStore } from '@/store/Menu'
import { storeToRefs } from 'pinia'
import Icons from '@/composables/Icons'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: 'Sidebar',
  setup() {
    const route = useRoute()
    const menuStore = useMenuStore()
    const { getMenu } = storeToRefs(menuStore) // теперь getMenu — это реактивное значение

    const menu = computed(() => {
      const items = []
      for (const key in getMenu.value) {
        const item = getMenu.value[key]
        items.push({
          active: key == route.name,
          title: item.name,
          to: { name: key }, // используем ключ как route name,
          icon: Icons[key],
        })
      }
      return items
    })

    return {
      menu,
    }
  },
})
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
              <li>
                <router-link :class="[{'active-m21': itemMenu.active }]" :to="itemMenu?.to">
                  <component style="width: 16px; height: 16px" :is="itemMenu?.icon" />
                  <span>{{ itemMenu.title }}</span>
                  <!--                  <ChevronLeftIcon style="width: 16px; height: 16px; margin-left: auto" />-->
                </router-link>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped lang="scss"></style>
