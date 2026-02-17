<script setup>
import { computed, useSlots } from 'vue';

const slots = useSlots();
const hasActions = computed(() => slots.actions?.());
</script>

<template>
    <div class="rounded-md border-2 border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <slot></slot>

        <section v-if="hasActions" class="flex flex-col justify-between gap-2 border-t-2 border-zinc-200 p-4 sm:flex-row dark:border-zinc-700">
            <slot name="actions"></slot>
        </section>
    </div>
</template>
