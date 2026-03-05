<script setup>
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
    <header data-test="header" class="mb-4 border-b-2 border-zinc-200 bg-white dark:border-zinc-700 dark:bg-transparent">
        <div class="container mx-auto max-w-4xl p-4">
            <div class="flex w-full flex-col items-center justify-between sm:flex-row">
                <div class="mb-2 flex items-center justify-between sm:mb-0">
                    <AppLogo :link="true" />
                </div>

                <div class="text-center text-secondary sm:text-right">Secure, one-time secret sharing made simple.</div>
            </div>
        </div>
    </header>
</template>
