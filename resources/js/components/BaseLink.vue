<script setup>
import { computed, useAttrs } from 'vue';
import { RouterLink } from 'vue-router';

const props = defineProps({
    to: [String, Object],
    fontWeight: {
        type: String,
        default: 'font-medium',
    },
});

const attrs = useAttrs();

const isRouterLink = computed(() => !!props.to);
</script>

<template>
    <component
        :is="isRouterLink ? RouterLink : 'a'"
        :to="isRouterLink ? to : undefined"
        v-bind="attrs"
        class="border-b-2 border-transparent text-sky-500 transition-all hover:border-sky-500 focus-visible:border-b-2 focus-visible:border-sky-500 focus-visible:outline-hidden active:border-transparent active:text-sky-600"
        :class="[fontWeight]"
        draggable="false"
    >
        <slot />
    </component>
</template>
