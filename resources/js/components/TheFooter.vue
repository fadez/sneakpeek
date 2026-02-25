<script setup>
import BaseLink from '@/components/BaseLink.vue';

const appName = import.meta.env.VITE_APP_NAME;
const commitHash = __COMMIT_HASH__;
</script>

<template>
    <footer data-test="footer" class="container mx-auto my-4 max-w-4xl px-4">
        <div class="text-secondary flex justify-between">
            <div>© {{ new Date().getFullYear() }} {{ appName }}. All rights reserved.</div>

            <div>
                View source code on
                <BaseLink href="https://github.com/fadez/sneakpeek" target="_blank">GitHub</BaseLink>
            </div>
        </div>
        <div class="text-muted flex justify-between">
            <div>
                Built with ❤️ in 🇺🇦 by
                <BaseLink href="https://github.com/fadez" target="_blank">@fadez</BaseLink>
            </div>
            <div v-if="commitHash">
                Current version:
                <BaseLink :href="`https://github.com/fadez/sneakpeek/commit/${commitHash}`" target="_blank">{{ commitHash }}</BaseLink>
            </div>
        </div>
    </footer>
</template>
