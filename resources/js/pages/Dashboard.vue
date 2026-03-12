<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { echo } from '@laravel/echo-vue';
import { getStatistics } from '@/api';
import StatisticGrid from '@/components/StatisticGrid.vue';
import StatisticGridItem from '@/components/StatisticGridItem.vue';

const statistics = ref({
    secrets_created: 0,
    secrets_revealed: 0,
    secrets_expired: 0,
    secrets_burned: 0,
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
    <div>
        <div class="my-4">
            <div class="mb-2 text-2xl font-semibold text-title">Welcome to the dashboard.</div>
            <div class="text-secondary">Thought you'd be interested to know.</div>
        </div>

        <StatisticGrid class="sm:grid-cols-2 lg:grid-cols-4">
            <StatisticGridItem title="Secrets created" icon="fa-solid fa-lock" :value="statistics.secrets_created" />
            <StatisticGridItem
                title="Secrets revealed"
                icon="fa-solid fa-unlock"
                icon-bg="bg-emerald-500"
                :value="statistics.secrets_revealed"
            />
            <StatisticGridItem
                title="Secrets expired"
                icon="fa-solid fa-hourglass"
                icon-bg="bg-yellow-500"
                :value="statistics.secrets_expired"
            />
            <StatisticGridItem title="Secrets burned" icon="fa-solid fa-fire" icon-bg="bg-rose-500" :value="statistics.secrets_burned" />
        </StatisticGrid>
    </div>
</template>
