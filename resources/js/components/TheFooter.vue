<script setup>
import BaseLink from '@/components/BaseLink.vue';
</script>

<template>
    <footer data-test="footer" class="container mx-auto my-4 max-w-3xl px-4">
        <div class="text-center text-zinc-700 dark:text-zinc-400">
            Built with ❤️ in 🇺🇦 by
            <BaseLink href="https://github.com/fadez" target="_blank">@fadez</BaseLink>
        </div>
        <div class="mt-4 text-center text-zinc-700 dark:text-zinc-400">
            View source code on
            <BaseLink href="https://github.com/fadez/sneakpeek" target="_blank">GitHub</BaseLink>
        </div>
    </footer>
</template>
