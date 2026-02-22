<script setup>
import { computed, useAttrs } from 'vue';
import { RouterLink } from 'vue-router';

const attrs = useAttrs();

const props = defineProps({
    to: [String, Object],
});

const linkClasses = [
    'text-sky-500',
    'active:text-sky-600',
    'border-b-2',
    'border-transparent',
    'hover:border-sky-500',
    'active:border-transparent',
    'focus-visible:outline-hidden',
    'focus-visible:border-b-2',
    'focus-visible:border-sky-500',
    'transition-all',
].join(' ');

const isRouterLink = computed(() => !!props.to);
</script>

<template>
    <component :is="isRouterLink ? RouterLink : 'a'" :to="isRouterLink ? props.to : undefined" :class="linkClasses" v-bind="attrs">
        <slot />
    </component>
</template>
