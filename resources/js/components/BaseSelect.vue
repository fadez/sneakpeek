<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
    },
});

const classes = computed(() => [
    'w-full',
    'bg-white',
    'dark:bg-black',
    'border-2',
    'border-zinc-200',
    'dark:border-zinc-700',
    'rounded-md',
    'p-3',
    'py-3.5',
    'dark:text-zinc-100',
    'focus:outline-hidden',
    'focus:border-sky-500',
    'cursor-pointer',
    'transition-all',
].join(' '));

const emit = defineEmits(['update:modelValue']);

const handleChange = (event) => {
    emit('update:modelValue', event.target.value);
    event.target.blur();
};
</script>

<template>
    <select
        :class="classes"
        :value="modelValue"
        @change="handleChange"
    >
        <slot></slot>
    </select>
</template>