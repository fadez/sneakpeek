<script setup>
import { useTemplateRef, defineModel } from 'vue';

const textarea = useTemplateRef('textarea');

const model = defineModel({
    type: String,
});

const classes = [
    'font-mono',
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
    'resize-none',
].join(' ');

defineExpose({
    focus: () => textarea.value?.focus(),
    select: () => textarea.value?.select(),
});
</script>

<template>
    <textarea ref="textarea" :class="classes" :value="model" @input="model = $event.target.value"></textarea>
</template>
