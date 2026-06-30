<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { RouterLink, type RouteLocationRaw } from 'vue-router';

const { to, fontWeightClass = 'font-medium' } = defineProps<{
    to?: RouteLocationRaw;
    fontWeightClass?: string;
}>();

const attrs = useAttrs();

const isRouterLink = computed(() => !!to);
</script>

<template>
    <component
        :is="isRouterLink ? RouterLink : 'a'"
        :to="isRouterLink ? to : undefined"
        v-bind="attrs"
        class="group relative font-medium text-sky-500 transition-all focus-visible:outline-hidden active:text-sky-600"
        :class="[fontWeightClass]"
        draggable="false"
    >
        <slot />

        <span
            class="pointer-events-none absolute bottom-[-0.75] block h-0.5 w-full bg-transparent transition-all group-hover:bg-sky-500 group-focus-visible:bg-sky-500 group-active:bg-sky-600"
        />
    </component>
</template>
