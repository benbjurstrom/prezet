import path from 'path'
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import { watchAndRun } from 'vite-plugin-watch-and-run'
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/prezet.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        watchAndRun([
            {
                name: 'prezet:index',
                watch: path.resolve('prezet/**/*.(md|jpg|png|webp)'),
                run: 'php artisan prezet:index',
                delay: 1000,
                // watchKind: ['add', 'change', 'unlink'], // (default)
            }
        ])
    ],
});
