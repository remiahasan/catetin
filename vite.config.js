import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Dev-container HMR: accept connections on all interfaces inside the
    // `vite` service, and tell the browser (on the host) where the HMR
    // websocket lives. `vite build` ignores this block, so production
    // output is unaffected.
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: process.env.VITE_HMR_HOST ?? 'localhost',
        },
    },
});
