<script setup>
import NumberFlow from '@number-flow/vue';

const {
    title = '',
    icon = '',
    iconBg = 'bg-sky-500',
    value,
    prefix = '',
    suffix = '',
} = defineProps({
    title: String,
    icon: String,
    iconBg: String,
    value: {
        type: [String, Number],
        required: true,
    },
    prefix: String,
    suffix: String,
});
</script>

<template>
    <div
        class="flex items-center gap-4 overflow-hidden rounded-md border-2 border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900"
    >
        <div
            v-if="icon"
            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-md text-xl text-white lg:h-14 lg:w-14 lg:text-2xl"
            :class="[iconBg]"
        >
            <i :class="icon"></i>
        </div>
        <div class="flex min-w-0 flex-1 flex-col">
            <div
                v-if="title"
                class="truncate text-sm text-muted"
            >
                {{ title }}
            </div>
            <NumberFlow
                class="font-mono text-2xl leading-4 font-semibold lg:leading-6"
                :value="value"
                :prefix="prefix"
                :suffix="suffix"
            />
        </div>
    </div>
</template>
