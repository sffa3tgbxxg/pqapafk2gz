import { createApp } from "vue";
import App from "@/views/App.vue";
import "./bootstrap";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import router from "@/router/index.js";
import { createPinia } from "pinia";
import { useMenuStore } from "./store/Menu";
import { useNotification } from "@/store/Notification.js";

const app = createApp(App);
const pinia = createPinia();
app.use(pinia);

app.use(router);

const menuStore = useMenuStore();

if (localStorage.getItem("token") != null) {
  menuStore.loadMenu();
}

app.mixin({
  methods: {
    copyText(text) {
      const textarea = document.createElement("textarea");
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand("copy");
      document.body.removeChild(textarea);
      useNotification().showNotification("Текст скопирован", "success");
    },
  },
});

app.component("VueDatePicker", VueDatePicker);
app.mount("#app");
