<script setup>
import { onMounted, onUnmounted } from 'vue';
import { useNotificationStore } from '@/stores/notifications';
import TheHeader from '@/components/TheHeader.vue';
import TheFooter from '@/components/TheFooter.vue';

const notify = useNotificationStore();

const handleBrowserOffline = () => {
    notify.internetDisconnected();
};

const handleBrowserOnline = () => {
    notify.internetReconnected();
};

onMounted(() => {
    window.addEventListener('offline', handleBrowserOffline);
    window.addEventListener('online', handleBrowserOnline);
});

onUnmounted(() => {
    window.removeEventListener('offline', handleBrowserOffline);
    window.removeEventListener('online', handleBrowserOnline);
});
</script>

<template>
    <TheHeader />

    <div class="container mx-auto max-w-4xl px-4">
        <RouterView />
    </div>

    <TheFooter />
</template>
