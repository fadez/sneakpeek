<script setup>
import { computed, useSlots } from 'vue';

const slots = useSlots();
const hasActions = computed(() => slots.actions?.());
</script>

<template>
    <div
        class="bg-white dark:bg-black rounded-md border-2 border-zinc-200 dark:border-zinc-700"
    >
        <slot></slot>

        <section
            v-if="hasActions"
            class="p-4 border-t-2 border-zinc-200 dark:border-zinc-700 flex flex-col sm:flex-row gap-2 justify-between"
        >
            <slot name="actions"></slot>
        </section>
    </div>
</template>
