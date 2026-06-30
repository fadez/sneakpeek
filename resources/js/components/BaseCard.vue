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
            class="card-actions"
        >
            <slot name="actions"></slot>
        </section>
    </div>
</template>
