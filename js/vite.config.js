import { resolve } from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
  root: '.',
  build: {
    outDir: '../public/css',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        style: resolve(__dirname, 'src/js/style.js')
      },
      output: {
        entryFileNames: '[name].js',
        assetFileNames: 'style.css',
      }
    }
  },
  css: {
    postcss: './postcss.config.js'
  }
});
