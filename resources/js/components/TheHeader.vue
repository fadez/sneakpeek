<script setup>
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
    <header
        data-test="header"
        class="mb-4 border-b-2 border-zinc-200 bg-white dark:border-zinc-700 dark:bg-transparent"
    >
        <div class="page-container py-4">
            <div class="flex w-full flex-col justify-between md:flex-row md:items-center">
                <div class="flex items-center justify-between">
                    <AppLogo :link="true" />
                </div>

                <div class="mt-2 flex flex-col md:mt-0">
                    <div class="text-secondary">Secure, one-time secret sharing made simple.</div>
                </div>
            </div>
        </div>
    </header>
</template>
