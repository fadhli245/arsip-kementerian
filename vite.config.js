import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/login.css',
                'resources/js/login.js',
                'resources/css/pegawai-dashboard.css',
                'resources/js/pegawai-dashboard.js',
                'resources/css/admin-dashboard.css',
                'resources/js/admin-dashboard.js',
            ],
            refresh: true,
        }),
    ],
});
