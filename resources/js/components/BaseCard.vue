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
