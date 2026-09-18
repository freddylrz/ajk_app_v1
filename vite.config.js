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

                 // Area client — 1 entry per halaman (dipanggil lewat @vite() di blade)
                 'resources/js/client/dashboard/index.js',
                 'resources/js/client/simulasi-premi/index.js',
                 'resources/js/client/penutupan/input-data.js',
                 'resources/js/client/penutupan/list-data.js',
                 'resources/js/client/penutupan/terbit-polis.js',
                 'resources/js/client/penutupan/detail.js',
                 'resources/js/client/penutupan/update-data.js',
                 'resources/js/client/klaim/input-data.js',
                 'resources/js/client/klaim/data.js',
                 'resources/js/client/klaim/detail.js',

                 // Area publik — halaman tanpa login
                 'resources/js/public/simulasi-premi.js',
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
