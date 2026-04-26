<script setup>
const { link = false } = defineProps({
    link: Boolean,
});
</script>

<template>
    <component
        :is="link ? 'RouterLink' : 'div'"
        v-bind="link ? { to: '/' } : {}"
        class="flex items-center rounded-md select-none"
        :class="{ 'focus-visible:ring-2 focus-visible:outline-hidden dark:focus-visible:ring-white': link }"
    >
        <img
            src="/public/logo.svg"
            class="size-12"
            alt="SneakPeek logo"
        />
        <div class="p-1 text-logo">SneakPeek</div>
    </component>
</template>
