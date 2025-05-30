import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],     // основният ти входен файл
      publicDirectory: 'public',          // Laravel публична папка
      buildDirectory: 'build',            // -> public/build/
      refresh: true,                      // автоматичен reload при dev
    }),
    vue(),
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js', // 👈 Ето това оправя грешката
    },
  },
  server: { 
    https: false,
    host: true,
    port: 3009,
    hmr: {host: 'localhost', protocol: 'ws'},
  },
}); 