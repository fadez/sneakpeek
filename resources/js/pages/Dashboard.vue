<script setup lang="ts">
import type { AppStatistics } from '@/types';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { echo } from '@laravel/echo-vue';
import { LucideFlame, LucideHourglass, LucideLockKeyhole, LucideLockKeyholeOpen } from '@lucide/vue';
import { getStatistics } from '@/api';
import StatisticGrid from '@/components/StatisticGrid.vue';
import StatisticGridItem from '@/components/StatisticGridItem.vue';

const statistics = ref<AppStatistics>({
    secrets_created: 0,
    secrets_revealed: 0,
    secrets_expired: 0,
    secrets_burned: 0,
});

onMounted(async () => {
    statistics.value = await getStatistics();

    echo()
        .channel('dashboard')
        .listen('.statistic.updated', (e: AppStatistics) => {
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
