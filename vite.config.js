import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import { execSync } from 'child_process';

// Get the current short Git commit hash to embed in the Vite build
const commitHash = (() => {
    try {
        return execSync('git rev-parse --short HEAD').toString().trim();
    } catch {
        return null;
    }
})();

export default defineConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            vue: path.resolve(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
    define: {
        __COMMIT_HASH__: JSON.stringify(commitHash),
    },
});
