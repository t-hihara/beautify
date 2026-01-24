import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/common/css/app.css',
        'resources/manager/js/app.ts',
        'resources/user/js/app.ts',
      ],
      refresh: true,
    }),
    tailwindcss(),
    vue(),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources'),
      '@manager': path.resolve(__dirname, './resources/manager/js'),
      '@user': path.resolve(__dirname, './resources/user/js'),
      '@common': path.resolve(__dirname, './resources/common/js'),
    },
  },
});
