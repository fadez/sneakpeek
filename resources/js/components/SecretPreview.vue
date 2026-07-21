<script setup lang="ts">
import BaseBadge from '@/components/BaseBadge.vue';
import { LucideKeyRound, LucideLockKeyhole } from '@lucide/vue';

defineProps<{
    passphraseProtected?: boolean;
}>();
</script>

<template>
    <div class="flex items-center justify-between gap-2 py-2 font-mono select-none">
        <div class="flex min-w-0 flex-1 items-center">
            <span class="overflow-hidden text-clip text-zinc-950 blur-sm dark:text-white">
                {{ '•'.repeat(111) }}
            </span>
        </div>

        <div class="flex shrink-0 flex-row items-end gap-2">
            <BaseBadge :icon="LucideLockKeyhole">Encrypted</BaseBadge>

            <BaseBadge
                v-if="passphraseProtected"
                :icon="LucideKeyRound"
            >
                Passphrase-protected
            </BaseBadge>
        </div>
    </div>
</template>
