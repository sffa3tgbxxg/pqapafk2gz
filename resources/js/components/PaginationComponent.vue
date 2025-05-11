<script lang="ts">
import { defineComponent, computed } from "vue";

export default defineComponent({
  name: "PaginationComponent",
  props: {
    currentPage: {
      type: Number,
      default: 1,
    },
    lastPage: {
      type: Number,
      required: true,
    },
    maxOffset: {
      type: Number,
      default: 6, // Максимальное количество отображаемых страниц
    },
  },
  setup(props, { emit }) {
    // Вычисляемый массив страниц
    const pagesSet = computed(() => {
      const pages = [];
      const halfOffset = Math.floor(props.maxOffset / 2);
      let startPage = Math.max(1, props.currentPage - halfOffset);
      let endPage = Math.min(props.lastPage, startPage + props.maxOffset - 1);

      // Корректируем startPage, если endPage достиг конца
      if (endPage - startPage + 1 < props.maxOffset) {
        startPage = Math.max(1, endPage - props.maxOffset + 1);
      }

      // Добавляем страницы в массив
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
      }

      // Добавляем первую страницу, если она не включена
      if (startPage > 1) {
        pages.unshift(1);
        if (startPage > 2) pages.splice(1, 0, "..."); // Добавляем многоточие
      }

      // Добавляем последнюю страницу, если она не включена
      if (endPage < props.lastPage) {
        if (endPage < props.lastPage - 1) pages.push("..."); // Добавляем многоточие
        pages.push(props.lastPage);
      }

      return pages;
    });

    // Метод для переключения страницы
    const changePage = (page: number) => {
      if (page !== props.currentPage && page !== "...") {
        emit("handle", page);
      }
    };

    return {
      pagesSet,
      changePage,
    };
  },
});
</script>

<template>
  <div class="n381">
    <div class="n382">
      <!-- Список страниц -->
      <template v-for="(page, index) in pagesSet" :key="index">
        <div
          class="n383"
          :class="{ 'n383-selected': page === currentPage, 'n383-ellipsis': page === '...' }"
          @click="changePage(page)"
        >
          {{ page }}
        </div>
      </template>
    </div>
  </div>
</template>
