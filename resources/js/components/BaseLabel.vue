<script setup>
const props = defineProps({
    required: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label class="inline-flex w-fit items-center text-sm font-semibold">
        <slot></slot>
        <span
            v-if="required"
            class="pointer-events-none ml-1 text-rose-500 select-none"
        >
            •
        </span>
    </label>
</template>
