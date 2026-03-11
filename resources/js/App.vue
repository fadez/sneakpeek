<script setup>
import { onMounted, onUnmounted } from 'vue';
import { useFeatureStore } from '@/stores/features';
import { useNotificationStore } from '@/stores/notifications';
import TheHeader from '@/components/TheHeader.vue';
import TheFooter from '@/components/TheFooter.vue';

const features = useFeatureStore();
const notify = useNotificationStore();

const handleBrowserOffline = () => {
    notify.internetDisconnected();
};

const handleBrowserOnline = () => {
    notify.internetReconnected();
};

onMounted(() => {
    features.reload();

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

    <div class="page-container">
        <RouterView />
    </div>

    <TheFooter />
</template>
