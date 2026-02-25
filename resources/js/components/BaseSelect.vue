<script setup>
import { computed, useAttrs } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const attrs = useAttrs();

const props = defineProps({
    modelValue: {
        type: String,
    },
});

const emit = defineEmits(['update:modelValue']);

const classes = computed(() =>
    [
        'w-full',
        'bg-white',
        'dark:bg-black',
        'border-2',
        'border-zinc-200',
        'dark:border-zinc-700',
        'rounded-md',
        'px-3',
        'py-3.5',
        'focus:outline-hidden',
        'focus:border-sky-500',
        'cursor-pointer',
        'appearance-none',
        'select-none',
        'transition-all',
    ].join(' '),
);

const handleChange = (event) => {
    emit('update:modelValue', event.target.value);
    event.target.blur();
};
</script>

<template>
    <div class="relative">
        <select v-bind="attrs" :class="classes" :value="modelValue" @change="handleChange">
            <slot></slot>
        </select>
        <i class="fa-solid fa-chevron-down text-muted pointer-events-none absolute top-1/2 right-3 -translate-y-1/2"></i>
    </div>
</template>
