import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  build: {
    outDir: '../dist', // Xuất file ra thư mục "assets"
    emptyOutDir: false, // Không xóa toàn bộ thư mục khi build
    rollupOptions: {
      output: {
        entryFileNames: 'vue-app.js', // Giữ tên file JS cố định
        assetFileNames: 'vue-style.css' // Giữ tên file CSS cố định
      }
    }
  }
});
