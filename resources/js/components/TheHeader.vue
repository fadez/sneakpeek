<script setup>
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
    <header data-test="header" class="mb-4 border-b-2 border-zinc-200 bg-white dark:border-zinc-700 dark:bg-transparent">
        <div class="page-container py-4">
            <div class="flex w-full flex-col items-center justify-between md:flex-row">
                <div class="mb-2 flex items-center justify-between md:mb-0">
                    <AppLogo :link="true" />
                </div>

                <div class="flex flex-col">
                    <div class="text-center text-secondary md:text-right">Secure, one-time secret sharing made simple.</div>
                </div>
            </div>
        </div>
    </header>
</template>
