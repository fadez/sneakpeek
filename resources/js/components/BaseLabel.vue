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
        <span v-if="required" class="text-red-500 ml-1">*</span>
    </label>
</template>
