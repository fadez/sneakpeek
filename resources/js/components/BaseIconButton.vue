<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'primary',
        validator(value) {
            return ['primary', 'success', 'danger', 'warning', 'light'].includes(value);
        },
    },
    icon: {
        type: String,
        default: '',
    },
    active: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'default',
        validator(value) {
            return ['toast', 'default'].includes(value);
        },
    },
});

const baseClasses = [
    'flex',
    'shrink-0',
    'cursor-pointer',
    'items-center',
    'justify-center',
    'rounded-md',
    'border-2',
    'transition',
    'select-none',
].join(' ');

const sizeClasses = {
    toast: 'h-7 w-7',
    default: 'h-10 w-10 text-lg',
};

// prettier-ignore
const buttonClasses = computed(() => {
    const typeClasses = {
        primary: props.active
            ? 'border-sky-500 bg-sky-100 text-sky-500'
            : 'hover:border-sky-500 hover:bg-sky-100 hover:text-sky-500 active:border-sky-500 active:bg-sky-200 active:text-sky-500',
        success: props.active
            ? 'border-emerald-500 bg-emerald-100 text-emerald-500'
            : 'hover:border-emerald-500 hover:bg-emerald-100 hover:text-emerald-500 active:border-emerald-500 active:bg-emerald-200 active:text-emerald-500',
        danger: props.active
            ? 'border-rose-500 bg-rose-100 text-rose-500'
            : 'hover:border-rose-500 hover:bg-rose-100 hover:text-rose-500 active:border-rose-500 active:bg-rose-200 active:text-rose-500',
        warning: props.active
            ? 'border-yellow-500 bg-yellow-100 text-yellow-500'
            : 'hover:border-yellow-500 hover:bg-yellow-100 hover:text-yellow-500 active:border-yellow-500 active:bg-yellow-200 active:text-yellow-500',
        light: props.active
            ? 'border-zinc-500 bg-zinc-100'
            : 'hover:border-zinc-950 hover:bg-zinc-200 active:border-zinc-950 active:bg-zinc-300 border-zinc-200 bg-white',
    };

    const classes = [
        baseClasses,
        props.active ? '' : 'border-zinc-200 bg-white',
        typeClasses[props.type],
        sizeClasses[props.size],
        props.disabled ? 'pointer-events-none opacity-50' : '',
        !props.active ? 'text-zinc-950' : '',
    ]
        .filter(Boolean)
        .join(' ');

    return classes;
});
</script>

<template>
    <div :class="buttonClasses">
        <i :class="icon" class="fa-sm"></i>
    </div>
</template>
