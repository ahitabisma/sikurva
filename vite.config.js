import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        legacy({
            targets: ['defaults', 'not IE 11', 'Safari >= 12'], // Menurunkan target ke Safari 12+
            modernPolyfills: true,
            polyfills: [
                'es.array.at',
                'es.array.find-last',
                'es.array.find-last-index',
                'es.object.has-own',
            ],
            renderLegacyChunks: true,
        }),
    ],
    build: {
        sourcemap: false,
    },
});
