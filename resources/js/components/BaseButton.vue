<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';
import type { ButtonType } from '@/types';
import { computed } from 'vue';
import BaseSpinner from '@/components/BaseSpinner.vue';

const {
    leadingIcon,
    trailingIcon,
    loading = false,
    disabled = false,
    type = 'primary',
} = defineProps<{
    leadingIcon?: LucideIcon;
    trailingIcon?: LucideIcon;
    loading?: boolean;
    disabled?: boolean;
    type?: ButtonType;
}>();

// prettier-ignore
const typeClasses: Record<ButtonType, string> = {
    primary: [
        'text-white',
        'border-transparent',
        'bg-sky-500',
        'hover:bg-sky-400',
        'active:bg-sky-500',
        'dark:text-white',
    ].join(' '),

    success: [
        'text-white',
        'border-transparent',
        'bg-emerald-500',
        'hover:bg-emerald-400',
        'active:bg-emerald-500',
        'dark:text-white',
    ].join(' '),

    danger: [
        'text-white',
        'border-transparent',
        'bg-rose-500',
        'hover:bg-rose-400',
        'active:bg-rose-500',
        'dark:text-white',
    ].join(' '),

    secondary: [
        'text-white',
        'border-transparent',
        'bg-zinc-500',
        'hover:bg-zinc-400',
        'active:bg-zinc-500',
        'dark:text-white',
        'dark:bg-zinc-600',
        'dark:hover:bg-zinc-500',
        'dark:active:bg-zinc-600',
    ].join(' '),

    light: [
        'text-zinc-950',
        'bg-white',
        'border-zinc-950',
        'hover:bg-zinc-200',
        'active:bg-zinc-300',
        'dark:text-zinc-100',
        'dark:bg-zinc-900',
        'dark:border-zinc-500',
        'dark:hover:bg-zinc-700',
        'dark:hover:border-zinc-100',
        'dark:active:bg-zinc-950',
    ].join(' '),
};

const buttonClasses = computed(() => typeClasses[type]);

// Return position if spinner should render in a specific position, or null if it should be hidden
const spinnerPosition = computed<'leading' | 'trailing' | null>(() => {
    if (!loading) return null;

    return trailingIcon && !leadingIcon ? 'trailing' : 'leading';
});
</script>

<template>
    <button
        class="relative flex cursor-pointer items-center justify-center gap-1.5 rounded-md border-2 px-4 py-3 whitespace-nowrap transition-all select-none focus-visible:border-black focus-visible:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:focus-visible:border-white"
        :class="buttonClasses"
        :disabled="disabled || loading"
    >
        <BaseSpinner v-if="spinnerPosition === 'leading'" />
        <component
            v-else-if="leadingIcon"
            :is="leadingIcon"
            class="size-5"
        ></component>

        <slot />

        <BaseSpinner v-if="spinnerPosition === 'trailing'" />
        <component
            v-else-if="trailingIcon && !loading"
            :is="trailingIcon"
            class="size-5"
        ></component>
    </button>
</template>
