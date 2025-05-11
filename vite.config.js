import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { fileURLToPath, URL } from "node:url";

export default defineConfig({
  server: { port: 3001, host: "127.0.0.1" },
  plugins: [
    vue(),
    laravel({
      input: ["resources/css/inc/default.scss", "resources/js/app.js"],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./resources/js", import.meta.url)),
      src: fileURLToPath(new URL("./resources/images", import.meta.url)),
    },
  },
});
