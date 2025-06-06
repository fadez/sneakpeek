<script setup>
defineProps({
    modelValue: {
        type: String,
    },
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
    'dark:text-zinc-100',
    'focus:outline-hidden',
    'focus:border-sky-500',
    'placeholder:text-zinc-400',
    'dark:placeholder:text-zinc-500',
    'transition-all',
];

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <input
        :class="classes"
        :value="modelValue"
        @input="emit('update:modelValue', $event.target.value)"
    />
</template>
