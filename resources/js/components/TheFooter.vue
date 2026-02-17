<script setup>
import BaseLink from '@/components/BaseLink.vue';

const commitHash = __COMMIT_HASH__;
</script>

<template>
    <footer data-test="footer" class="container mx-auto my-4 max-w-4xl px-4">
        <div class="text-center text-zinc-700 dark:text-zinc-400">
            Built with ❤️ in 🇺🇦 by
            <BaseLink href="https://github.com/fadez" target="_blank">@fadez</BaseLink>
        </div>
        <div class="mt-4 text-center text-zinc-700 dark:text-zinc-400">
            View source code on
            <BaseLink href="https://github.com/fadez/sneakpeek" target="_blank">GitHub</BaseLink>
        </div>
        <div v-if="commitHash" class="mt-4 text-center text-xs text-zinc-400 dark:text-zinc-500">
            Current version:
            <BaseLink :href="`https://github.com/fadez/sneakpeek/commit/${commitHash}`" target="_blank">{{ commitHash }}</BaseLink>
        </div>
    </footer>
</template>
