<script setup lang="ts">
import type { Secret } from '@/types';
import { ref, watch, useTemplateRef, onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { echo } from '@laravel/echo-vue';
import { getSecret, revealSecret } from '@/api';
import { useNotificationStore } from '@/stores/notifications';
import { useClipboard } from '@/composables/useClipboard';
import { useElementFocus } from '@/composables/useElementFocus';
import { useSecretExpirationProgress } from '@/composables/useSecretExpirationProgress';
import BaseAlert from '@/components/BaseAlert.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';
import SecretPreview from '@/components/SecretPreview.vue';
import { LucideCopy, LucideLockKeyholeOpen } from '@lucide/vue';

const route = useRoute();
const router = useRouter();
const notify = useNotificationStore();
const { copyToClipboard } = useClipboard();
const { focus, focusAndSelect } = useElementFocus();

const passphraseInput = useTemplateRef<HTMLInputElement | null>('passphrase-input');
const secretContentTextarea = useTemplateRef<HTMLTextAreaElement | null>('secret-content-textarea');

const accessToken = ref<string>('');
const secret = ref<Secret | null>(null);
const secretContent = ref<string | null>(null);
const passphrase = ref<string>('');
const isRevealingSecret = ref<boolean>(false);

const fetchSecret = async (refresh?: boolean): Promise<void> => {
    try {
        secret.value =
            refresh === true ? await getSecret(route.params.id as string) : await getSecret(route.params.id as string, accessToken.value);

        router.replace({
            name: 'secret',
            params: { id: route.params.id },
            hash: '',
        });

        if (secret.value?.is_passphrase_protected) {
            focusPassphraseInput();
        }
    } catch {
        router.replace({ name: 'home' });
    }
};

const refreshSecret = (): Promise<void> => {
    return fetchSecret(true);
};

const handleSecretReveal = async (): Promise<void> => {
    if (isRevealingSecret.value) return;

    isRevealingSecret.value = true;

    try {
        secretContent.value = await revealSecret(route.params.id as string, {
            passphrase: passphrase.value,
            access_token: accessToken.value,
        });

        notify.secretRevealed();
    } catch {
        clearPassphraseInput();
        focusPassphraseInput();
    } finally {
        isRevealingSecret.value = false;
    }
};

const focusPassphraseInput = (): void => {
    focus(passphraseInput);
};

const clearPassphraseInput = (): void => {
    passphrase.value = '';
};

const copySecret = (): void => {
    focusAndSelect(secretContentTextarea);

    if (secretContent.value == null) return;

    copyToClipboard(secretContent.value, {
        onSuccess: notify.secretMessageCopied,
        onError: notify.failedToCopySecretMessage,
    });
};

const resetPage = (): void => {
    secret.value = null;
    secretContent.value = null;
    accessToken.value = '';
    isRevealingSecret.value = false;
    clearPassphraseInput();
};

const handleSecretIdChange = async (newId: string | string[] | undefined, oldId: string | string[] | undefined): Promise<void> => {
    resetPage();

    accessToken.value = route.hash.slice(1);

    await fetchSecret();

    if (oldId) echo().leave(`secrets.${oldId}`);

    if (!newId) return;

    const id = Array.isArray(newId) ? newId[0] : newId;

    echo()
        .channel(`secrets.${id}`)
        .listen('.secret.revealed', () => {
            if (!secret.value) return;
            secret.value.is_available = false;
            secret.value.is_revealed = true;
        })
        .listen('.secret.burned', () => {
            if (!secret.value) return;
            secret.value.is_available = false;
            secret.value.is_burned = true;
        });
};

const handlePageShow = (event: PageTransitionEvent): void => {
    if (event.persisted) fetchSecret();
};

useSecretExpirationProgress(secret, refreshSecret);

watch(() => route.params.id, handleSecretIdChange, { immediate: true });

onMounted(() => {
    window.addEventListener('pageshow', handlePageShow);
});

onBeforeUnmount(() => {
    if (secret.value?.id) echo().leave(`secrets.${secret.value.id}`);
    window.removeEventListener('pageshow', handlePageShow);
});
</script>

<template>
    <div
        v-if="!secret"
        class="my-4"
    >
        <BaseCard class="min-h-secret-card-skeleton">
            <BaseLoader />
        </BaseCard>
    </div>
    <div
        v-else
        class="my-4"
    >
        <BaseCard v-if="secretContent">
            <section class="form">
                <BaseAlert type="info">
                    This secret message has been deleted from our servers. You can close this window when done.
                </BaseAlert>
            </section>

            <section class="border-t-2 border-zinc-200 bg-zinc-75 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <BaseTextarea
                    id="secret-content-textarea"
                    ref="secret-content-textarea"
                    data-test="secret-content-textarea"
                    class="font-mono"
                    v-model="secretContent"
                    rows="7"
                    readonly
                />
            </section>

            <template #actions>
                <BaseButton
                    type="primary"
                    :leading-icon="LucideCopy"
                    @click="copySecret"
                >
                    Copy to Clipboard
                </BaseButton>
            </template>
        </BaseCard>
        <BaseCard
            :show-actions="secret.is_available"
            v-else
        >
            <section class="p-4">
                <BaseAlert
                    v-if="secret.is_burned"
                    type="danger"
                >
                    Secret has been burned by its creator.
                </BaseAlert>
                <BaseAlert
                    v-else-if="secret.is_revealed"
                    type="danger"
                >
                    Secret has been revealed by someone else! Looks like there might be a problem...
                </BaseAlert>
                <BaseAlert
                    v-else-if="secret.is_expired"
                    type="danger"
                >
                    Secret has expired. You'll need to ask for a fresh one.
                </BaseAlert>
                <BaseAlert
                    v-else
                    type="info"
                >
                    Your secret message is ready. We'll show it only once — make sure you're ready to save it.
                </BaseAlert>
            </section>

            <section
                class="border-t-2 border-zinc-200 bg-zinc-75 p-4 dark:border-zinc-700 dark:bg-zinc-800"
                :class="{ 'rounded-b-sm': !secret.is_available }"
            >
                <SecretPreview :passphrase-protected="secret.is_passphrase_protected" />
            </section>

            <template #actions>
                <BaseInput
                    v-if="secret.is_passphrase_protected"
                    id="passphrase-input"
                    ref="passphrase-input"
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
                    :leading-icon="LucideLockKeyholeOpen"
                    :disabled="isRevealingSecret || (secret.is_passphrase_protected && !passphrase)"
                    :loading="isRevealingSecret"
                    @click="handleSecretReveal"
                >
                    Reveal Secret
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
