<script setup>
import { computed, useSlots } from 'vue';

const slots = useSlots();
const hasActions = computed(() => slots.actions?.());
const hasTitle = computed(() => slots.title?.());
</script>

<template>
    <div class="card">
        <section v-if="hasTitle" class="card-title">
            <slot name="title"></slot>
        </section>

        <slot></slot>

        <section
            v-if="hasActions"
            class="flex flex-col justify-between gap-2 border-t-2 border-zinc-200 p-4 sm:flex-row dark:border-zinc-700"
        >
            <slot name="actions"></slot>
        </section>
    </div>
</template>
