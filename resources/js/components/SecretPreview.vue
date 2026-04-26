<script setup>
import BaseBadge from '@/components/BaseBadge.vue';

const { passphraseProtected = false } = defineProps({
    passphraseProtected: Boolean,
});
</script>

<template>
    <div class="flex items-center justify-between gap-2 py-2 font-mono select-none">
        <div class="flex min-w-0 flex-1 items-center">
            <span class="overflow-hidden text-clip text-zinc-950 blur-sm dark:text-white">
                {{ '•'.repeat(111) }}
            </span>
        </div>
        <div class="flex shrink-0 flex-row items-end gap-2">
            <BaseBadge icon="fa-solid fa-lock">Encrypted</BaseBadge>
            <BaseBadge
                v-if="passphraseProtected"
                icon="fa-solid fa-key"
            >
                Passphrase-protected
            </BaseBadge>
        </div>
    </div>
</template>
