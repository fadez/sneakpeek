import { execSync } from 'child_process';
import { readFileSync } from 'fs';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import { defineConfig, lazyPlugins } from 'vite-plus';

const pkg = JSON.parse(readFileSync('./package.json', 'utf-8'));

// Get the current short Git commit hash to embed in the Vite build
const commitHash = (() => {
    try {
        return execSync('git rev-parse --short HEAD').toString().trim();
    } catch {
        return null;
    }
})();

export default defineConfig({
    define: {
        __AUTHOR_NAME__: JSON.stringify(pkg.author.name),
        __AUTHOR_URL__: JSON.stringify(pkg.author.url),
        __COMMIT_HASH__: JSON.stringify(commitHash),
        __REPOSITORY_URL__: JSON.stringify(pkg.repository.url),
    },
    plugins: lazyPlugins(() => [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                bunny('Rubik', {
                    weights: [700],
                }),
                bunny('Outfit', {
                    weights: [400, 500, 600, 700, 900],
                }),
                bunny('JetBrains Mono', {
                    weights: [400, 600, 700],
                }),
            ],
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
    ]),
    server: {
        watch: {
            ignored: ['**/.agents/**', '**/.claude/**', '**/.cursor/**', '**/.junie/**', '**/vendor/**'],
        },
    },
    lint: {
        jsPlugins: [
            {
                name: 'vite-plus',
                specifier: 'vite-plus/oxlint-plugin',
            },
        ],
        options: {
            denyWarnings: true,
            typeAware: true,
        },
        categories: {
            correctness: 'error',
        },
        rules: {
            'vite-plus/prefer-vite-plus-imports': 'error',
            'import/consistent-type-specifier-style': ['error', 'prefer-top-level'],
            'typescript/consistent-type-imports': 'error',
            'sort-imports': ['error', { ignoreDeclarationSort: true }],
        },
        ignorePatterns: [
            'vendor/**',
            'node_modules/**',
            'public/**',
            'bootstrap/ssr/**',
            'tailwind.config.js',
            'resources/js/actions/**',
            'resources/js/components/ui/*',
            'resources/js/routes/**',
            'resources/js/wayfinder/**',
        ],
    },
    fmt: {
        printWidth: 140,
        tabWidth: 4,
        singleQuote: true,
        semi: true,
        singleAttributePerLine: true,
        htmlWhitespaceSensitivity: 'css',
        sortTailwindcss: {
            functions: ['clsx', 'cn', 'cva'],
            entryPoint: 'resources/css/app.css',
        },
        ignorePatterns: ['.github/**', 'composer.json', 'resources/js/components/ui/*', 'resources/views/mail/*'],
    },
});
