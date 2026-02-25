<script setup>
const props = defineProps({
    icon: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <span class="text-muted flex items-center gap-1 rounded-full bg-zinc-200 px-2 py-1 text-xs font-medium dark:bg-zinc-700">
        <i v-if="icon" :class="icon"></i>
        <slot></slot>
    </span>
</template>
