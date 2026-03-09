<script setup>
import { computed } from 'vue';
import BaseSpinner from '@/components/BaseSpinner.vue';

const props = defineProps({
    iconBefore: {
        type: String,
        default: '',
    },
    iconAfter: {
        type: String,
        default: '',
    },
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'success', 'danger', 'light'].includes(value),
    },
});

// prettier-ignore
const typeClasses = {
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

    'light': [
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

const buttonClasses = computed(() => typeClasses[props.type]);

// Return position if spinner should render in a specific position, or null if it should be hidden
const spinnerPosition = computed(() => {
    if (!props.loading) return null;

    return props.iconAfter && !props.iconBefore ? 'after' : 'before';
});
</script>

<template>
    <button
        class="flex cursor-pointer items-center justify-center gap-1.5 rounded-md border-2 px-4 py-2.5 whitespace-nowrap transition-all select-none focus-visible:border-black focus-visible:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:focus-visible:border-white"
        :class="buttonClasses"
        :disabled="disabled"
    >
        <BaseSpinner v-if="spinnerPosition === 'before'" />
        <i v-else-if="iconBefore" :class="iconBefore" />

        <span>
            <slot />
        </span>

        <BaseSpinner v-if="spinnerPosition === 'after'" />
        <i v-else-if="iconAfter && !loading" :class="iconAfter" />
    </button>
</template>
