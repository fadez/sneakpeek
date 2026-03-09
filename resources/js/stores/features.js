import { defineStore } from 'pinia';
import { getFeatures, deactivateFeature } from '@/api';

export const useFeatureStore = defineStore('features', {
    state: () => ({
        features: {},
    }),

    getters: {
        isActive: (state) => (feature) => !!state.features[feature],
        value: (state) => (feature) => state.features[feature] ?? null,
    },

    actions: {
        async reload() {
            this.features = await getFeatures();
        },
        async deactivate(feature) {
            await deactivateFeature(feature);
            await this.reload();
        },
    },
});
