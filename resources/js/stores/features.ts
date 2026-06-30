import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { FeaturesMap } from '@/types';
import { deactivateFeature, listFeatures } from '@/api';

export const useFeatureStore = defineStore('features', () => {
    const features = ref<FeaturesMap>({});

    const isActive = computed(() => {
        return (feature: string) => Boolean(features.value[feature]);
    });

    const value = computed(() => {
        return (feature: string) => features.value[feature] ?? null;
    });

    async function refresh() {
        features.value = await listFeatures();
    }

    async function deactivate(feature: string) {
        await deactivateFeature(feature);
        await refresh();
    }

    return {
        features,
        isActive,
        value,
        refresh,
        deactivate,
    };
});
