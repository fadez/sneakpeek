<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';
import type { Value } from '@number-flow/vue';
import NumberFlow from '@number-flow/vue';

const {
    title,
    icon,
    iconClass = 'bg-sky-500',
    value,
    prefix,
    suffix,
} = defineProps<{
    title?: string;
    icon?: LucideIcon;
    iconClass?: string;
    value: Value;
    prefix?: string;
    suffix?: string;
}>();
</script>

<template>
    <div
        class="flex items-center gap-4 overflow-hidden rounded-md border-2 border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900"
    >
        <div
            v-if="icon"
            class="flex size-12 shrink-0 items-center justify-center rounded-md text-white lg:size-14"
            :class="iconClass"
        >
            <component
                :is="icon"
                class="size-7 lg:size-8"
            />
        </div>
        <div class="flex min-w-0 flex-1 flex-col">
            <div
                v-if="title"
                class="truncate text-sm text-muted"
            >
                {{ title }}
            </div>
            <NumberFlow
                class="font-mono text-2xl leading-4 font-semibold select-none lg:leading-6"
                :value="value"
                :prefix="prefix"
                :suffix="suffix"
            />
        </div>
    </div>
</template>
