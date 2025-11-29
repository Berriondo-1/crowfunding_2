import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        // Force HMR to point to the host you use in the browser (e.g., localhost)
        hmr: {
            host: process.env.VITE_DEV_HOST || 'localhost',
            port: 5173,
        },
        // Polling improves file change detection on mounted volumes
        watch: {
            usePolling: true,
        },
        origin:
            process.env.VITE_DEV_ORIGIN ||
            `http://${process.env.VITE_DEV_HOST || 'localhost'}:5173`,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
