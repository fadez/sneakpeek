<script setup>
import TheHeader from '@/components/TheHeader.vue';
import TheFooter from '@/components/TheFooter.vue';
</script>

<template>
    <TheHeader />

    <div class="container max-w-3xl mx-auto px-4">
        <RouterView />
    </div>

    <TheFooter />
</template>
