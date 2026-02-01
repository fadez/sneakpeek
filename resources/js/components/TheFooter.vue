<script setup>
import BaseLink from '@/components/BaseLink.vue';
</script>

<template>
    <footer class="container max-w-3xl mx-auto px-4 my-4">
        <div class="text-center text-zinc-700 dark:text-zinc-400">
            Built with ❤️ in 🇺🇦 by
            <BaseLink href="https://github.com/fadez" target="_blank"">@fadez</BaseLink>
        </div>
        <div class="text-center text-zinc-700 dark:text-zinc-400 mt-4">
            View source code on
            <BaseLink href="https://github.com/fadez/sneakpeek" target="_blank">GitHub</BaseLink>
        </div>

        <div class="text-center text-xs text-zinc-600 dark:text-zinc-300 mt-4">
            © {{ new Date().getFullYear() }} SneakPeek
        </div>
    </footer>
</template>
