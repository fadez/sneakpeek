import { execSync } from 'child_process';
import { readFileSync } from 'fs';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
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
    lint: {
        plugins: ['oxc', 'typescript', 'unicorn', 'import'],
        categories: {
            correctness: 'error',
        },
        rules: {
            'vite-plus/prefer-vite-plus-imports': 'error',
            'typescript/consistent-type-imports': 'error',
        },
        env: {
            builtin: true,
        },
        options: {
            typeAware: true,
            typeCheck: true,
        },
        jsPlugins: [
            {
                name: 'vite-plus',
                specifier: 'vite-plus/oxlint-plugin',
            },
        ],
    },
    fmt: {
        sortTailwindcss: {
            stylesheet: './resources/css/app.css',
        },
        semi: true,
        singleQuote: true,
        bracketSameLine: false,
        singleAttributePerLine: true,
        htmlWhitespaceSensitivity: 'ignore',
        printWidth: 140,
        sortPackageJson: false,
        ignorePatterns: [
            '.claude/skills/',
            '.cursor/skills/',
            'storage/',
            'AGENTS.md',
            'CLAUDE.md',
            'boost.json',
            '.mcp.json',
            '**/mcp.json',
        ],
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
        vue(),
    ]),
    define: {
        __AUTHOR_NAME__: JSON.stringify(pkg.author.name),
        __AUTHOR_URL__: JSON.stringify(pkg.author.url),
        __COMMIT_HASH__: JSON.stringify(commitHash),
        __REPOSITORY_URL__: JSON.stringify(pkg.repository.url),
    },
});
