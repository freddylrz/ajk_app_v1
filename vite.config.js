import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                 'resources/js/app.js',
                 'resources/js/auth/login.js',
                 'resources/js/auth/logout.js',
                 'resources/js/helper_cookie.js',
                 'resources/js/initialize.js',
                 'resources/js/utilities/management_branch.js',
                 'resources/js/utilities/management_user.js',
                ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        hmr: false,
    },
});
