<script setup>
import { computed } from 'vue';

const props = defineProps({
    iconBefore: {
        type: String,
        default: '',
    },
    iconAfter: {
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
        validator: (value) => ['primary', 'secondary', 'success', 'danger', 'light'].includes(value),
    },
});

// prettier-ignore
const baseClasses = [
    'px-4',
    'py-2.5',
    'select-none',
    'rounded-md',
    'flex',
    'gap-1',
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
        'border-zinc-300',
        'hover:bg-zinc-100',
        'active:bg-zinc-200',
        'dark:text-zinc-100',
        'dark:bg-zinc-900',
        'dark:border-zinc-700',
        'dark:hover:bg-zinc-800',
        'dark:active:bg-transparent',
    ].join(' '),
};

const buttonClasses = computed(() => {
    return `${baseClasses} ${typeClasses[props.type]}`;
});
</script>

<template>
    <button :class="buttonClasses" :disabled="disabled">
        <i v-if="iconBefore" :class="iconBefore" />
        <span>
            <slot></slot>
        </span>
        <i v-if="iconAfter" :class="iconAfter" />
    </button>
</template>
