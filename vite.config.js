import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

/**
 * Fonts are SELF-HOSTED, not fetched from a CDN. The laravel-vite-plugin `bunny()` helper
 * is deliberately absent: a font CDN is an extra DNS lookup and TLS handshake on the
 * critical path, and hands a third party a log of every visitor. Geist ships from
 * resources/fonts/ via @font-face in app.css, which Vite fingerprints into public/build.
 */
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // hero3d and gsap are dynamically imported and must stay in their own chunks:
        // a page without the 3D hero should never download it.
        target: 'es2020',
    },
    server: {
        watch: { ignored: ['**/storage/framework/views/**'] },
    },
});
