<script setup lang="ts">
import type { Secret } from '@/types';
import { computed, nextTick, onBeforeUnmount, onMounted, ref, useTemplateRef, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { echo } from '@laravel/echo-vue';
import { LucideCopy, LucideLockKeyholeOpen } from '@lucide/vue';
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

const route = useRoute();
const router = useRouter();
const notify = useNotificationStore();
const { copyToClipboard } = useClipboard();
const { focus, focusAndSelect } = useElementFocus();

const passphraseInput = useTemplateRef<HTMLInputElement | null>('passphrase-input');
const secretContentTextarea = useTemplateRef<HTMLTextAreaElement | null>('secret-content-textarea');

const accessToken = ref<string>('');
const secret = ref<Secret | null | undefined>(undefined);
const secretContent = ref<string | null>(null);
const passphrase = ref<string>('');
const isRevealingSecret = ref<boolean>(false);

const hasAccessToken = computed<boolean>(() => {
    return !!accessToken.value;
});

const fetchSecret = async (): Promise<void> => {
    try {
        secret.value = await getSecret(route.params.id as string, accessToken.value);

        if (secret.value?.is_passphrase_protected) {
            await nextTick();

            focusPassphraseInput();
        }
    } catch {
        secret.value = null;
    }
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

const extractAccessToken = (): void => {
    accessToken.value = route.hash.slice(1);

    // Secret access tokens are stored in the URL hash fragment to prevent server-side logging, analytics tracking, or accidental leakage via Referer header,
    // so here we capture and strip it from the URL to minimize its exposure
    if (accessToken.value) {
        router.replace({
            name: 'secret',
            params: { id: route.params.id },
            hash: '',
        });
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
    secret.value = undefined;
    secretContent.value = null;
    accessToken.value = '';
    isRevealingSecret.value = false;
    clearPassphraseInput();
};

const handleAccessTokenChange = async (): Promise<void> => {
    // Guard against the loop caused when extractAccessToken() strips the hash, retriggering this watcher
    if (!route.hash) return;

    resetPage();

    extractAccessToken();

    await fetchSecret();
};

const handleSecretIdChange = async (newId: string | string[] | undefined, oldId: string | string[] | undefined): Promise<void> => {
    console.log('handleSecretIdChange');

    resetPage();

    extractAccessToken();

    await fetchSecret();

    const previousSecretId = Array.isArray(oldId) ? oldId[0] : oldId;
    const currentSecretId = Array.isArray(newId) ? newId[0] : newId;

    if (previousSecretId) {
        echo().leave(`secrets.${previousSecretId}`);
    }

    if (!currentSecretId) return;

    echo()
        .channel(`secrets.${currentSecretId}`)
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
    // event.persisted is true when the page is restored from the browser's back/forward cache (bfcache)
    if (event.persisted) fetchSecret();
};

useSecretExpirationProgress(secret, fetchSecret);

watch(() => route.params.id, handleSecretIdChange, { immediate: true });

watch(() => route.hash, handleAccessTokenChange);

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
        v-if="secret === undefined"
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

            <section class="bg-zinc-75 border-t-2 border-zinc-200 p-4 dark:border-zinc-700 dark:bg-zinc-800">
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
            :show-actions="secret != null && secret.is_available && hasAccessToken"
            v-else
        >
            <section class="p-4">
                <BaseAlert
                    v-if="secret === null"
                    type="danger"
                >
                    This secret is nowhere to be found. Maybe it was deleted. Maybe it never existed. We recommend asking for a new one.
                </BaseAlert>
                <BaseAlert
                    v-else-if="secret.is_burned"
                    type="danger"
                >
                    This secret has been burned by its creator.
                </BaseAlert>
                <BaseAlert
                    v-else-if="secret.is_revealed"
                    type="danger"
                >
                    Someone else has revealed the secret! Looks like there might be a problem...
                </BaseAlert>
                <BaseAlert
                    v-else-if="secret.is_expired"
                    type="danger"
                >
                    Secret has expired. We recommend asking for a new one.
                </BaseAlert>
                <BaseAlert
                    v-else-if="hasAccessToken"
                    type="info"
                >
                    Your secret message is ready. We'll show it only once — make sure you're ready to save it.
                </BaseAlert>
            </section>

            <section
                class="bg-zinc-75 border-t-2 border-zinc-200 p-4 dark:border-zinc-700 dark:bg-zinc-800"
                :class="{ 'rounded-b-sm': secret && !secret.is_available }"
            >
                <SecretPreview :passphrase-protected="secret != null && secret.is_passphrase_protected" />
            </section>

            <template #actions>
                <BaseInput
                    v-if="secret && secret.is_passphrase_protected"
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
                    :disabled="isRevealingSecret || (secret != null && secret.is_passphrase_protected && !passphrase)"
                    :loading="isRevealingSecret"
                    @click="handleSecretReveal"
                >
                    Reveal Secret
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
