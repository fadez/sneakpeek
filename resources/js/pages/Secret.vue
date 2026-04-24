<script setup>
import { ref, watch, useTemplateRef, onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { echo } from '@laravel/echo-vue';
import { getSecret, revealSecret } from '@/api';
import { useNotificationStore } from '@/stores/notifications';
import { useClipboard } from '@/composables/useClipboard';
import { useElementFocus } from '@/composables/useElementFocus';
import { useSecretExpirationProgress } from '@/composables/useSecretExpirationProgress';
import BaseAlert from '@/components/BaseAlert.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';
import SecretPreview from '@/components/SecretPreview.vue';

const route = useRoute();
const router = useRouter();
const notify = useNotificationStore();
const { copyToClipboard } = useClipboard();
const { focus, focusAndSelect } = useElementFocus();

const passphraseInput = useTemplateRef('passphrase-input');
const secretContentTextarea = useTemplateRef('secret-content-textarea');

const secret = ref(null);
const accessToken = ref(route.hash.slice(1));
const secretContent = ref(null);
const passphrase = ref('');
const isRevealingSecret = ref(false);

const fetchSecret = async (refresh) => {
    try {
        secret.value = refresh === true ? await getSecret(route.params.id) : await getSecret(route.params.id, accessToken.value);

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

const refreshSecret = () => {
    return fetchSecret(true);
};

const handleSecretReveal = async () => {
    if (isRevealingSecret.value) return;

    isRevealingSecret.value = true;

    try {
        secretContent.value = await revealSecret(route.params.id, {
            passphrase: passphrase.value,
            access_token: accessToken.value,
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
    focusAndSelect(secretContentTextarea);

    copyToClipboard(secretContent.value, {
        onSuccess: notify.secretMessageCopied,
        onError: notify.failedToCopySecretMessage,
    });
};

const handleSecretIdChange = (newId, oldId) => {
    resetPage();
    fetchSecret();

    if (oldId) echo().leave(`secrets.${oldId}`);

    if (!newId) return;

    echo()
        .channel(`secrets.${newId}`)
        .listen('.secret.revealed', (e) => {
            secret.value.is_available = false;
            secret.value.is_revealed = true;
        })
        .listen('.secret.burned', (e) => {
            secret.value.is_available = false;
            secret.value.is_burned = true;
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

const secretExpirationProgress = useSecretExpirationProgress(secret, refreshSecret);

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
    <div v-if="!secret" class="my-4">
        <BaseCard class="min-h-secret-card-skeleton">
            <BaseLoader />
        </BaseCard>
    </div>
    <div v-else class="my-4">
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
                <BaseButton type="primary" icon-before="fa-solid fa-copy" @click="copySecret">Copy to Clipboard</BaseButton>
            </template>
        </BaseCard>
        <BaseCard :show-actions="secret.is_available" v-else>
            <section class="p-4">
                <BaseAlert v-if="secret.is_burned" type="danger">Secret has been burned by its creator.</BaseAlert>
                <BaseAlert v-else-if="secret.is_revealed" type="danger">
                    Secret has been revealed by someone else! Looks like there might be a problem...
                </BaseAlert>
                <BaseAlert v-else-if="secret.is_expired" type="danger">Secret has expired. You'll need to ask for a fresh one.</BaseAlert>
                <BaseAlert v-else type="info">
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
                    icon-before="fa-solid fa-unlock"
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
