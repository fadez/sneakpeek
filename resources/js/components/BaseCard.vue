<script setup lang="ts">
import { computed, useSlots } from 'vue';

const { showActions = true } = defineProps<{
    showActions?: boolean;
}>();

const slots = useSlots();

const hasTitle = computed(() => !!slots.title?.());
const hasActions = computed(() => !!slots.actions?.());
</script>

<template>
    <div class="card">
        <section
            v-if="hasTitle"
            class="card-title"
        >
            <slot name="title" />
        </section>

        <slot />

        <section
            v-if="hasActions && showActions"
            class="card-actions"
        >
            <slot name="actions" />
        </section>
    </div>
</template>

<style scoped>
@reference "#app.css";

.card {
    @apply flex flex-col rounded-md border-2 border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900;
}

.card + .card {
    @apply mt-4;
}

.card-title {
    @apply flex rounded-t-sm border-b-2 border-zinc-200 bg-zinc-75 p-4 font-semibold text-title dark:border-zinc-700 dark:bg-black;
}

.card-actions {
    @apply flex flex-col justify-between gap-2 border-t-2 border-zinc-200 p-4 md:flex-row dark:border-zinc-700;
}
</style>
