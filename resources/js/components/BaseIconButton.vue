<script setup>
import { computed } from 'vue';

const {
    type = 'info',
    icon = '',
    colored = false,
    active = false,
    disabled = false,
    size = 'default',
} = defineProps({
    type: {
        type: String,
        validator: (value) => ['success', 'danger', 'info', 'warning', 'light'].includes(value),
    },
    icon: String,
    colored: Boolean,
    active: Boolean,
    disabled: Boolean,
    size: {
        type: String,
        validator: (value) => ['sm', 'default'].includes(value),
    },
});

const sizeClasses = {
    default: 'h-10 w-10 text-lg',
    sm: 'h-7 w-7',
};

const buttonClasses = computed(() => {
    let baseBorderClass = '';
    let baseTextClass = '';

    const coloredBorders = {
        success: 'border-emerald-500',
        danger: 'border-rose-500',
        info: 'border-sky-500',
        warning: 'border-yellow-500',
        light: 'border-zinc-950',
    };

    if (!active) {
        if (colored) {
            baseBorderClass = coloredBorders[type];
        } else {
            baseBorderClass = 'border-zinc-200';
        }
    }

    const coloredTextColors = {
        info: 'text-sky-500',
        success: 'text-emerald-500',
        danger: 'text-rose-500',
        warning: 'text-yellow-500',
        light: 'text-zink-950',
    };

    if (!active) {
        if (colored) {
            baseTextClass = coloredTextColors[type];
        } else {
            baseTextClass = 'text-zinc-950';
        }
    }

    const commonNonColoredClasses = active ? '' : 'bg-white';
    const commonColoredClasses = active ? '' : 'bg-white dark:bg-transparent';

    const nonColoredClasses = {
        success: active
            ? 'border-emerald-500 bg-emerald-100 text-emerald-500'
            : 'hover:border-emerald-500 hover:bg-emerald-100 hover:text-emerald-500 active:border-emerald-500 active:bg-emerald-200 active:text-emerald-500',
        danger: active
            ? 'border-rose-500 bg-rose-100 text-rose-500'
            : 'hover:border-rose-500 hover:bg-rose-100 hover:text-rose-500 active:border-rose-500 active:bg-rose-200 active:text-rose-500',
        info: active
            ? 'border-sky-500 bg-sky-100 text-sky-500'
            : 'hover:border-sky-500 hover:bg-sky-100 hover:text-sky-500 active:border-sky-500 active:bg-sky-200 active:text-sky-500',
        warning: active
            ? 'border-yellow-500 bg-yellow-100 text-yellow-500'
            : 'hover:border-yellow-500 hover:bg-yellow-100 hover:text-yellow-500 active:border-yellow-500 active:bg-yellow-200 active:text-yellow-500',
        light: active
            ? 'border-zinc-950 bg-zinc-200 text-zinc-950'
            : 'hover:border-zinc-950 hover:bg-zinc-200 active:border-zinc-950 active:bg-zinc-300',
    };

    const coloredActiveStateClasses = {
        success: 'active:bg-emerald-200',
        danger: 'active:bg-rose-200',
        info: 'active:bg-sky-200',
        warning: 'active:bg-yellow-200',
        light: 'active:bg-zinc-300',
    };

    // prettier-ignore
    const coloredClasses = {
        success: active
            ? 'border-emerald-500 bg-emerald-100 text-emerald-500 active:bg-emerald-200'
            : 'hover:bg-emerald-100 hover:border-emerald-500 dark:hover:bg-emerald-900 dark:active:bg-emerald-950',
        danger: active
            ? 'border-rose-500 bg-rose-100 text-rose-500 active:bg-rose-200'
            : 'hover:bg-rose-100 dark:hover:bg-rose-900 dark:active:bg-rose-950',
        info: active
            ? 'border-sky-500 bg-sky-100 text-sky-500 dark:active:bg-sky-200'
            : 'hover:bg-sky-100 dark:hover:bg-sky-900 dark:active:bg-sky-950',
        warning: active
            ? 'border-yellow-500 bg-yellow-100 text-yellow-500 active:bg-yellow-200'
            : 'hover:bg-yellow-100 dark:hover:bg-yellow-900 dark:active:bg-yellow-950',
        light: active
            ? 'border-zinc-950 dark:border-zinc-100 bg-zinc-200 dark:bg-zinc-700 text-zinc-950 dark:text-zinc-100 dark:active:bg-zinc-950'
            : 'hover:bg-zinc-200 dark:bg-zinc-900 dark:border-zinc-100 dark:hover:bg-zinc-700 dark:hover:border-zinc-100 dark:active:bg-zinc-950',
    };

    const classes = [
        baseBorderClass,
        baseTextClass,
        sizeClasses[size],
        colored ? coloredActiveStateClasses[type] : '',
        colored ? commonColoredClasses : commonNonColoredClasses,
        colored ? coloredClasses[type] : nonColoredClasses[type],
        disabled ? 'pointer-events-none opacity-50' : '',
    ]
        .filter(Boolean)
        .join(' ');

    return classes;
});
</script>

<template>
    <button
        class="flex shrink-0 cursor-pointer items-center justify-center rounded-md border-2 transition select-none focus-visible:ring-2 focus-visible:outline-hidden dark:focus-visible:ring-white"
        :class="buttonClasses"
        :disabled="disabled"
    >
        <i
            :class="icon"
            class="fa-sm"
        ></i>
    </button>
</template>
