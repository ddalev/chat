import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      publicDirectory: 'public',
      buildDirectory: 'build',
      refresh: true,
    }),
    vue(),
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
    },
  },
  server: { 
    https: false,
    host: true,
    port: 3009,
    hmr: {host: 'localhost', protocol: 'ws'},
  },
}); 