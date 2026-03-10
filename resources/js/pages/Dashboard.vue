<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { echo } from '@laravel/echo-vue';
import { getStatistics } from '@/api';
import BaseCard from '@/components/BaseCard.vue';
import BaseLoader from '@/components/BaseLoader.vue';

const statistics = ref(null);

const revealRate = computed(() => {
    if (!statistics.value) return '0';

    const value = Number(statistics.value.reveal_rate);

    return Number.isInteger(value) ? String(value) : value.toFixed(2);
});

onMounted(async () => {
    statistics.value = await getStatistics();

    echo()
        .channel('dashboard')
        .listen('.statistic.updated', (e) => {
            statistics.value = e;
        });
});

onBeforeUnmount(() => {
    echo().leave('dashboard');
});
</script>

<template>
    <div v-if="!statistics" class="my-4">
        <BaseCard class="min-h-dashboard-card-skeleton">
            <BaseLoader />
        </BaseCard>
    </div>
    <div v-else class="my-4">
        <BaseCard>
            <template #title>Dashboard</template>
            <div class="p-4">
                <div>Secrets created: {{ statistics.secrets_created }}</div>
                <div>Secrets revealed: {{ statistics.secrets_revealed }}</div>
                <div>Reveal rate: {{ revealRate }}%</div>
            </div>
        </BaseCard>
    </div>
</template>
