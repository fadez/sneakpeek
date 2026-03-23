<script setup>
import { computed } from 'vue';
import { useFeatureStore } from '@/stores/features';
import BaseLink from '@/components/BaseLink.vue';

const features = useFeatureStore();

const appName = import.meta.env.VITE_APP_NAME;
const authorUrl = __AUTHOR_URL__;
const repositoryUrl = __REPOSITORY_URL__;
const commitHash = __COMMIT_HASH__;
const currentYear = new Date().getFullYear();

const currentVersionUrl = computed(() => (commitHash ? `${repositoryUrl}/commit/${commitHash}` : null));

const abGroupLabel = computed(() => {
    const group = features.value('ab-group');

    if (!group) return null;

    const letter = group === 'control' ? 'A' : 'B';

    return `A/B group: ${group} (${letter})`;
});
</script>

<template>
    <footer data-test="footer" class="my-4">
        <div class="page-container">
            <div class="flex flex-col justify-between text-secondary md:flex-row">
                <div>© {{ currentYear }} {{ appName }}. All rights reserved.</div>

                <div class="bullet-divider">
                    <div class="inline-flex">
                        <BaseLink :to="{ name: 'dashboard' }">Dashboard</BaseLink>
                    </div>
                    <div class="inline-flex">
                        <BaseLink :to="{ name: 'ui' }">UI kit</BaseLink>
                    </div>
                    <div class="inline-flex">
                        <BaseLink :href="repositoryUrl" target="_blank">GitHub</BaseLink>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-between text-muted md:flex-row">
                <div class="inline-flex items-center">
                    Built with&nbsp;
                    <img src="../../images/heart.svg" alt="love" class="pointer-events-none h-6 w-5" />
                    &nbsp;in&nbsp;
                    <img src="../../images/flag-ua.svg" alt="Ukraine" class="pointer-events-none h-6 w-5" />
                    &nbsp;by&nbsp;
                    <BaseLink :href="authorUrl" target="_blank">@fadez</BaseLink>
                </div>
                <div class="bullet-divider">
                    <div v-if="abGroupLabel" class="inline-flex">{{ abGroupLabel }}</div>
                    <div v-if="currentVersionUrl" class="inline-flex">
                        Version:&nbsp;
                        <BaseLink :href="currentVersionUrl" target="_blank">
                            {{ commitHash }}
                        </BaseLink>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
