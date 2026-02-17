<script setup>
import { computed } from 'vue';

const props = defineProps({
    iconBefore: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'danger', 'outline-secondary'].includes(value),
    },
});

// prettier-ignore
const buttonClasses = computed(() => {
    const baseClasses = [
        'px-4',
        'py-3',
        'select-none',
        'rounded-md',
        'flex',
        'border-2',
        'items-center',
        'justify-center',
        'cursor-pointer',
        'whitespace-nowrap',
        'transition-all',
        'disabled:opacity-50',
        'disabled:pointer-events-none',
        'focus-visible:outline-hidden',
        'focus-visible:border-black',
        'dark:focus-visible:border-white',
    ].join(' ');

    const typeClasses = {
        primary: [
            'text-white',
            'border-transparent',
            'bg-sky-500',
            'hover:bg-sky-600',
            'active:bg-sky-700',
            'dark:text-white',
        ].join(' '),

        success: [
            'text-white',
            'border-transparent',
            'bg-emerald-500',
            'hover:bg-emerald-600',
            'active:bg-emerald-700',
            'dark:text-white',
        ].join(' '),

        danger: [
            'text-white',
            'border-transparent',
            'bg-red-500',
            'hover:bg-red-600',
            'active:bg-red-700',
            'dark:text-white',
        ].join(' '),

        secondary: [
            'text-white',
            'border-transparent',
            'bg-zinc-400',
            'hover:bg-zinc-500',
            'active:bg-zinc-600',
            'dark:text-white',
            'dark:bg-zinc-700',
            'dark:hover:bg-zinc-600',
            'dark:active:bg-zinc-700',
        ].join(' '),

        'outline-secondary': [
            'text-zinc-950',
            'border-zinc-300',
            'hover:bg-zinc-100',
            'active:bg-zinc-200',
            'dark:text-zinc-100',
            'dark:border-zinc-700',
            'dark:hover:bg-zinc-800',
            'dark:active:bg-transparent',
        ].join(' '),
    };

    return `${baseClasses} ${typeClasses[props.type]}`;
});
</script>

<template>
    <button :class="buttonClasses" :disabled="disabled">
        <i v-if="iconBefore" class="mr-1" :class="iconBefore" />
        <span>
            <slot></slot>
        </span>
    </button>
</template>
