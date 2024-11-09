import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
import { visualizer } from 'rollup-plugin-visualizer'


export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        AutoImport({
            resolvers: [ElementPlusResolver()],
          }),
        Components({
        resolvers: [ElementPlusResolver()],
        }),
        visualizer({
            open: true, // Automatically open the report in your browser
            filename: 'bundle-analysis.html', // Specify the output file name
        }),
    ],
        // build: {
        //     chunkSizeWarningLimit: 5000, // Increase to 1000 kB or your preferred limit
        // },
});
