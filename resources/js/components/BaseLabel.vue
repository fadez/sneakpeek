<script setup lang="ts">
const { required = false } = defineProps<{
    required?: boolean;
}>();
</script>

<template>
    <label class="inline-flex w-fit items-center text-sm font-semibold">
        <slot />

        <span
            v-if="required"
            class="pointer-events-none ml-1 text-rose-500 select-none"
        >
            •
        </span>
    </label>
</template>
