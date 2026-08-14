<script setup lang="ts">
import type { Statistics, StatisticsData } from '@/types';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { echo } from '@laravel/echo-vue';
import { LucideFlame, LucideHourglass, LucideLockKeyhole, LucideLockKeyholeOpen } from '@lucide/vue';
import { getStatistics } from '@/api';
import BasePageTitle from '@/components/BasePageTitle.vue';
import StatisticGrid from '@/components/StatisticGrid.vue';
import StatisticGridItem from '@/components/StatisticGridItem.vue';

const statistics = ref<Statistics>({
    secrets_created: 0,
    secrets_revealed: 0,
    secrets_expired: 0,
    secrets_burned: 0,
});

onMounted(async () => {
    const response = await getStatistics();

    statistics.value = response.statistics;

    echo()
        .channel('dashboard')
        .listen('.statistics.updated', (e: StatisticsData) => {
            statistics.value = e.statistics;
        });
});

onBeforeUnmount(() => {
    echo().leave('dashboard');
});
</script>

<template>
    <div>
        <BasePageTitle
            title="Welcome to the dashboard."
            description="Thought you'd be interested to know."
        />

        <StatisticGrid class="sm:grid-cols-2 lg:grid-cols-4">
            <StatisticGridItem
                title="Secrets created"
                :icon="LucideLockKeyhole"
                :value="statistics.secrets_created"
            />
            <StatisticGridItem
                title="Secrets revealed"
                :icon="LucideLockKeyholeOpen"
                icon-class="bg-emerald-500"
                :value="statistics.secrets_revealed"
            />
            <StatisticGridItem
                title="Secrets expired"
                :icon="LucideHourglass"
                icon-class="bg-yellow-500"
                :value="statistics.secrets_expired"
            />
            <StatisticGridItem
                title="Secrets burned"
                :icon="LucideFlame"
                icon-class="bg-rose-500"
                :value="statistics.secrets_burned"
            />
        </StatisticGrid>
    </div>
</template>
