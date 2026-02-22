<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
    },
});

const textarea = ref(null);

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
    'dark:text-zinc-100',
    'focus:outline-hidden',
    'focus:border-sky-500',
    'placeholder:text-zinc-400',
    'dark:placeholder:text-zinc-500',
    'transition-all',
    'resize-none',
].join(' ');

const emit = defineEmits(['update:modelValue']);

const handleInput = (event) => {
    emit('update:modelValue', event.target.value);
};

defineExpose({
    focus: () => textarea.value?.focus(),
    select: () => textarea.value?.select(),
});
</script>

<template>
    <textarea ref="textarea" :class="classes" :value="modelValue" @input="handleInput"></textarea>
</template>
