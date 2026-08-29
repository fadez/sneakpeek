<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';

defineProps<{
    icon?: LucideIcon;
}>();
</script>

<template>
    <span class="text-muted inline-flex items-center gap-1 rounded-full bg-zinc-200 px-2 py-1 text-xs font-medium dark:bg-zinc-700">
        <component
            v-if="icon"
            :is="icon"
            class="size-4"
        />

        <slot />
    </span>
</template>
