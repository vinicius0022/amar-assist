import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  server: {
    host: true, // ← Important for Docker!
    port: 5173,
    strictPort: true,
    watch: {
      usePolling: true, // ← Helps with file changes in Docker volumes (especially on Windows/Mac)
      interval: 100
    }
  }
})
