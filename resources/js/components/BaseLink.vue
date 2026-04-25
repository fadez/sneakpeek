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
        class="group relative text-sky-500 transition-all focus-visible:outline-hidden active:text-sky-600"
        :class="[fontWeight]"
        draggable="false"
    >
        <slot />
        <span
            class="pointer-events-none absolute bottom-[-0.75] block h-0.5 w-full bg-transparent transition-all group-hover:bg-sky-500 group-focus-visible:bg-sky-500 group-active:bg-sky-600"
        ></span>
    </component>
</template>
