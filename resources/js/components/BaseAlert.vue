<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'danger', 'info', 'warning'].includes(value),
    },
});

// prettier-ignore
const baseClasses = [
    'px-4',
    'py-3',
    'border-2',
    'rounded-md',
    'flex',
    'items-center',
    'gap-2',
].join(' ');

// prettier-ignore
const typeClasses = {
    success: [
        'bg-green-100',
        'dark:bg-green-950',
        'text-green-500',
        'border-green-500',
        'dark:border-green-600',
    ].join(' '),

    danger: [
        'bg-rose-100',
        'dark:bg-rose-950',
        'text-rose-500',
        'border-rose-500',
        'dark:border-rose-600',
    ].join(' '),

    info: [
        'bg-sky-100',
        'dark:bg-sky-950',
        'text-sky-500',
        'border-sky-500',
        'dark:border-sky-600',
    ].join(' '),

    warning: [
        'bg-yellow-100',
        'dark:bg-yellow-950',
        'text-yellow-500',
        'border-yellow-500',
        'dark:border-yellow-600',
    ].join(' '),
};

const typeIcons = {
    success: 'fa-solid fa-circle-check',
    danger: 'fa-solid fa-circle-exclamation',
    info: 'fa-solid fa-circle-info',
    warning: 'fa-solid fa-triangle-exclamation',
};

const alertClasses = computed(() => {
    return `${baseClasses} ${typeClasses[props.type]}`;
});

const iconClasses = computed(() => typeIcons[props.type]);
</script>

<template>
    <div :class="alertClasses">
        <i :class="iconClasses"></i>
        <div>
            <slot></slot>
        </div>
    </div>
</template>
