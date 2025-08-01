import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
   plugins: [
      vue(),
      vueDevTools(),
      tailwindcss()
   ],
   resolve: {
      alias: {
         '@': fileURLToPath(new URL('./src', import.meta.url))
      }
   },
   server: {
      allowedHosts: [
         'ifixforyou.local'
      ],
      host: 'ifixforyou.local',
      port: 5173,
      strictPort: true,
      https: false
   }
})