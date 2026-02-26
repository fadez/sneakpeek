<script setup>
import { useTemplateRef, defineModel } from 'vue';

const input = useTemplateRef('input');

const model = defineModel({
    type: [String, Number],
});

const classes = [
    'w-full',
    'bg-white',
    'dark:bg-black',
    'border-2',
    'border-zinc-200',
    'dark:border-zinc-700',
    'rounded-md',
    'p-3',
    'focus:outline-hidden',
    'focus:border-sky-500',
    'transition-all',
];

defineExpose({
    focus: () => input.value?.focus(),
    select: () => input.value?.select(),
});
</script>

<template>
    <input ref="input" :class="classes" :value="model" @input="model = $event.target.value" />
</template>
