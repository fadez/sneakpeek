<script setup>
import BaseLink from '@/components/BaseLink.vue';
</script>

<template>
    <footer class="container max-w-3xl mx-auto px-4 my-4">
        <div class="text-center text-zinc-700 dark:text-zinc-400">
            © {{ new Date().getFullYear() }}
            Built with ❤️ in 🇺🇦 by
            <BaseLink
                href="https://github.com/fadez"
                target="_blank"
            >
                @fadez
            </BaseLink>
        </div>
    </footer>
</template>
