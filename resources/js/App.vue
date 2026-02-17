<script setup>
import TheHeader from '@/components/TheHeader.vue';
import TheFooter from '@/components/TheFooter.vue';
</script>

<template>
    <TheHeader />

    <div class="container mx-auto max-w-4xl px-4">
        <RouterView />
    </div>

    <TheFooter />
</template>
