<script setup lang="ts">
const { title, description } = defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="page-title-container">
        <div class="page-title">{{ title }}</div>
        <div
            v-if="description"
            class="page-description"
        >
            {{ description }}
        </div>
    </div>
</template>

<style scoped>
@reference "#app.css";

.page-title-container {
    @apply my-4 flex flex-col gap-2;
}

.page-title {
    @apply text-2xl font-semibold text-title;
}

.page-description {
    @apply text-secondary;
}
</style>
