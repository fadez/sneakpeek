<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getSecret, revealSecret } from '@/api';
import { useNotificationStore } from '@/stores/notifications';
import { useClipboard } from '@/composables/useClipboard';
import { useElementFocus } from '@/composables/useElementFocus';
import BaseCard from '@/components/BaseCard.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseMessage from '@/components/BaseMessage.vue';
import BaseBadge from '@/components/BaseBadge.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';

const route = useRoute();
const router = useRouter();
const notify = useNotificationStore();
const { copyToClipboard } = useClipboard();
const { focus, focusAndSelect } = useElementFocus();

const passphraseInput = ref(null);
const secretContentInput = ref(null);

const secret = ref(null);
const secretContent = ref(null);
const passphrase = ref('');
const isRevealingSecret = ref(false);
const routeHash = ref(route.hash.slice(1));

const fetchSecret = async () => {
    resetPage();

    try {
        secret.value = await getSecret(route.params.id, routeHash.value);

        // Clear the access token from the URL hash to prevent accidental exposure in browser history, clipboard, or screenshots
        router.replace({
            name: 'secret',
            params: { id: route.params.id },
            hash: '',
        });

        // Auto-focus the passphrase input if the secret is passphrase-protected
        if (secret.value.is_passphrase_protected) {
            focusPassphraseInput();
        }
    } catch (error) {
        router.replace({ name: 'home' });
    }
};

const handleSecretReveal = async () => {
    if (isRevealingSecret.value) return;

    isRevealingSecret.value = true;

    try {
        secretContent.value = await revealSecret(route.params.id, {
            passphrase: passphrase.value,
            access_token: routeHash.value,
        });

        notify.secretRevealed();
    } catch (error) {
        clearPassphraseInput();
        focusPassphraseInput();
    } finally {
        isRevealingSecret.value = false;
    }
};

const focusPassphraseInput = () => {
    focus(passphraseInput);
};

const clearPassphraseInput = () => {
    passphrase.value = '';
};

const copySecret = () => {
    focusAndSelect(secretContentInput);

    copyToClipboard(secretContent.value, {
        onSuccess: notify.secretMessageCopied,
        onError: notify.failedToCopySecretMessage,
    });
};

const resetPage = () => {
    secret.value = null;
    secretContent.value = null;
    clearPassphraseInput();
    isRevealingSecret.value = false;
};

const handlePageShow = (event) => {
    if (event.persisted) fetchSecret();
};

watch(() => route.params.id, fetchSecret, { immediate: true });

onMounted(() => {
    window.addEventListener('pageshow', handlePageShow);
});

onUnmounted(() => {
    window.removeEventListener('pageshow', handlePageShow);
});
</script>

<template>
    <div v-if="!secret" class="my-4">
        <BaseCard class="min-h-secret-card-skeleton">
            <BaseLoader />
        </BaseCard>
    </div>
    <div v-else class="my-4">
        <BaseCard v-if="secretContent">
            <section class="form">
                <BaseMessage type="info">This secret message has been deleted from our servers. You can close this window when done.</BaseMessage>
            </section>

            <section class="border-t-2 border-zinc-200 bg-zinc-100 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <BaseTextarea ref="secretContentInput" data-test="secret-content" v-model="secretContent" rows="7" readonly />
            </section>

            <template #actions>
                <BaseButton type="primary" icon-before="fa-solid fa-copy" @click="copySecret">Copy to Clipboard</BaseButton>
            </template>
        </BaseCard>
        <BaseCard v-else>
            <section class="p-4">
                <BaseMessage type="info">Your secret message is ready. We'll show it only once — make sure you're ready to save it.</BaseMessage>
            </section>

            <section class="border-t-2 border-zinc-200 bg-zinc-100 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="flex items-center justify-between gap-2 py-2 font-mono select-none">
                    <div class="flex min-w-0 flex-1 items-center">
                        <span class="truncate text-zinc-950 blur-sm dark:text-white">
                            ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••
                        </span>
                    </div>
                    <div class="flex shrink-0 flex-row items-end gap-2">
                        <BaseBadge icon="fa-solid fa-lock">Encrypted</BaseBadge>
                        <BaseBadge v-if="secret.is_passphrase_protected" icon="fa-solid fa-key">Passphrase-protected</BaseBadge>
                    </div>
                </div>
            </section>

            <template #actions>
                <BaseInput
                    v-if="secret.is_passphrase_protected"
                    ref="passphraseInput"
                    data-test="passphrase-input"
                    type="password"
                    v-model="passphrase"
                    :disabled="isRevealingSecret"
                    placeholder="Enter passphrase..."
                    @keyup.enter="handleSecretReveal"
                />

                <BaseButton
                    data-test="reveal-secret-btn"
                    type="primary"
                    icon-before="fa-solid fa-unlock"
                    :disabled="isRevealingSecret || (secret.is_passphrase_protected && !passphrase)"
                    @click="handleSecretReveal"
                >
                    Reveal Secret
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
