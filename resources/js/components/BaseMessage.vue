<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'danger', 'info', 'warning'].includes(value),
    },
});

const messageClasses = computed(() => {
    const baseClasses = [
        'px-4',
        'py-3',
        'border-2',
        'rounded-md',
        'flex',
        'items-center',
        'gap-2',
    ].join(' ');

    const typeClasses = {
        info: [
            'bg-sky-100',
            'dark:bg-sky-950',
            'text-sky-500',
            'border-sky-500',
            'dark:border-sky-600',
        ].join(' '),
        success: [
            'bg-green-100',
            'dark:bg-green-950',
            'text-green-500',
            'border-green-500',
            'dark:border-green-600',
        ].join(' '),
        danger: [
            'bg-red-100',
            'dark:bg-red-950',
            'text-red-500',
            'border-red-500',
            'dark:border-red-600',
        ].join(' '),
        warning: [
            'bg-yellow-100',
            'dark:bg-yellow-950',
            'text-yellow-500',
            'border-yellow-500',
            'dark:border-yellow-600',
        ].join(' '),
    };

    return `${baseClasses} ${typeClasses[props.type]}`;
});

const iconClasses = computed(() => {
    const icons = {
        info: 'fa-solid fa-circle-info',
        success: 'fa-solid fa-circle-check',
        danger: 'fa-solid fa-circle-exclamation',
        warning: 'fa-solid fa-triangle-exclamation',
    };

    return icons[props.type];
});
</script>

<template>
    <div :class="messageClasses">
        <i class="fa-fw" :class="iconClasses"></i>
        <div>
            <slot></slot>
        </div>
    </div>
</template>
