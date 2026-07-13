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
                 'resources/js/client/dashboard.js',
                 'resources/js/client/penutupan-input.js',
                 'resources/js/client/penutupan-list.js',
                 'resources/js/client/penutupan-terbit-polis.js',
                 'resources/js/client/penutupan-detail.js',
                 'resources/js/client/penutupan-update.js',
                 'resources/js/client/klaim-laporan-awal.js',
                 'resources/js/client/klaim-formulir.js',
                 'resources/js/client/klaim-data.js',
                 'resources/js/client/klaim-detail.js',
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
