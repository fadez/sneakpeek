<script setup lang="ts">
import { useMotto } from '@/composables/useMotto';
import AppLogo from '@/components/AppLogo.vue';

const { motto } = useMotto();
</script>

<template>
    <header
        data-test="header"
        class="mb-4 border-b-2 border-zinc-200 bg-white dark:border-zinc-700 dark:bg-transparent"
    >
        <div class="page-container py-4">
            <div class="flex w-full flex-col justify-between md:flex-row md:items-center">
                <div class="flex items-center justify-between">
                    <AppLogo />
                </div>

                <div class="mt-2 flex flex-col md:mt-0">
                    <div class="text-secondary">{{ motto }}</div>
                </div>
            </div>
        </div>
    </header>
</template>
