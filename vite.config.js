import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  build: {
    outDir: 'assets/css',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'assets/scss/app.scss'),
        notion: resolve(__dirname, 'assets/scss/theme/_notion.scss')
      },
      output: {
        assetFileNames: '[name].[ext]'
      }
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: '@import "assets/scss/variables";'
      }
    }
  },
  server: {
    watch: {
      usePolling: true
    }
  }
}); 