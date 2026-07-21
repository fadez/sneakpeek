<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { LucideChevronDown } from '@lucide/vue';

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string | number>();

const attrs = useAttrs();

const selectClasses = computed(() =>
    'disabled' in attrs ? '' : 'group-hover:not-focus:border-zinc-325 dark:group-hover:not-focus:border-zinc-500',
);

const iconClasses = computed(() => ('disabled' in attrs ? '' : 'group-hover:text-secondary group-focus-within:text-secondary'));
</script>

<template>
    <div class="group relative">
        <select
            v-bind="attrs"
            class="w-full appearance-none rounded-md border-2 border-zinc-200 bg-white py-3 pr-10 pl-3 transition-all select-none focus:border-sky-500 focus:outline-hidden dark:border-zinc-700 dark:bg-black"
            :class="selectClasses"
            v-model="model"
        >
            <slot />
        </select>

        <LucideChevronDown
            class="pointer-events-none absolute top-1/2 right-3 size-6 -translate-y-1/2 text-muted transition-all"
            :class="iconClasses"
        />
    </div>
</template>
