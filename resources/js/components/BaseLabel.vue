<script setup>
const props = defineProps({
    required: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-400">
        <slot></slot>
        <span v-if="required" class="ml-1 text-red-500">*</span>
    </label>
</template>
