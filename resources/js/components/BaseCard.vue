<script setup>
import { computed, useSlots } from 'vue';

const slots = useSlots();

const { showActions = true } = defineProps({
    showActions: Boolean,
});

const hasTitle = computed(() => slots.title?.());
const hasActions = computed(() => slots.actions?.());
</script>

<template>
    <div class="card">
        <section
            v-if="hasTitle"
            class="card-title"
        >
            <slot name="title"></slot>
        </section>

        <slot></slot>

        <section
            v-if="hasActions && showActions"
            class="flex flex-col justify-between gap-2 border-t-2 border-zinc-200 p-4 md:flex-row dark:border-zinc-700"
        >
            <slot name="actions"></slot>
        </section>
    </div>
</template>
