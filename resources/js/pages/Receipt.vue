<script setup>
import { ref, computed, watch, useTemplateRef, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getSecret, deleteSecret } from '@/api';
import { useClipboard } from '@/composables/useClipboard';
import { useElementFocus } from '@/composables/useElementFocus';
import { formatDate } from '@/utils/formatDate';
import { useNotificationStore } from '@/stores/notifications';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseMessage from '@/components/BaseMessage.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLabel from '@/components/BaseLabel.vue';
import BaseBadge from '@/components/BaseBadge.vue';

const route = useRoute();
const router = useRouter();
const notify = useNotificationStore();
const { copyToClipboard } = useClipboard();
const { focus, focusAndSelect } = useElementFocus();

const secretLinkInput = useTemplateRef('secret-link-input');
const passphraseInput = useTemplateRef('passphrase-input');

const secret = ref(null);
const passphrase = ref('');
const showPassphraseInput = ref(false);
const isDeletingSecret = ref(false);

const hasAccessToken = computed(() => {
    if (!secret.value?.access_token) return false;

    return true;
});

const secretUrl = computed(() => {
    if (!hasAccessToken.value) return '';

    const path = router.resolve({
        name: 'secret',
        params: {
            id: secret.value.id,
        },
        hash: `#${secret.value.access_token}`,
    }).href;

    return `${window.location.origin}${path}`;
});

const fetchSecret = async () => {
    const state = window.history.state;

    // Retrieve secret from history state (passed during redirect)
    // and immediately clear it to prevent access via browser navigation
    if (state && state.secret) {
        secret.value = state.secret;

        // Clear the state in the browser history
        const newState = { ...state };
        delete newState.secret;
        window.history.replaceState(newState, '');

        return;
    }

    resetPage();

    try {
        secret.value = await getSecret(route.params.id);
    } catch (error) {
        router.replace({ name: 'home' });
    }
};

const handleSecretDelete = async () => {
    if (isDeletingSecret.value) return;

    // If the secret is passphrase-protected we need to show the passphrase input first
    if (secret.value.is_passphrase_protected && !showPassphraseInput.value) {
        showPassphraseInput.value = true;
        return;
    }

    isDeletingSecret.value = true;

    try {
        await deleteSecret(secret.value.id, {
            passphrase: passphrase.value,
            access_token: secret.value.access_token,
        });

        notify.secretDeleted();

        router.replace({ name: 'home' });
    } catch (error) {
        clearPassphraseInput();

        focusPassphraseInput();
    } finally {
        isDeletingSecret.value = false;
    }
};

const copySecretUrl = () => {
    selectSecretLinkInput();

    copyToClipboard(secretUrl.value, {
        onSuccess: notify.secretLinkCopied,
        onError: notify.failedToCopySecretLink,
    });
};

const cancelSecretDeletion = () => {
    clearPassphraseInput();
    hidePassphraseInput();
    selectSecretLinkInput();
};

const selectSecretLinkInput = () => {
    focusAndSelect(secretLinkInput);
};

const focusPassphraseInput = () => {
    focus(passphraseInput);
};

const clearPassphraseInput = () => {
    passphrase.value = '';
};

const hidePassphraseInput = () => {
    showPassphraseInput.value = false;
};

const handlePassphraseInputVisibilityChange = (inputVisible) => {
    if (inputVisible) {
        focusPassphraseInput();
    }
};

const resetPage = () => {
    secret.value = null;
    clearPassphraseInput();
    hidePassphraseInput();
    isDeletingSecret.value = false;
};

const handlePageShow = (event) => {
    if (event.persisted) fetchSecret();
};

watch(showPassphraseInput, handlePassphraseInputVisibilityChange, { flush: 'post' });

watch(() => route.params.id, fetchSecret, { immediate: true });

watch(secretLinkInput, selectSecretLinkInput, { once: true });

onMounted(() => {
    window.addEventListener('pageshow', handlePageShow);
});

onUnmounted(() => {
    window.removeEventListener('pageshow', handlePageShow);
});
</script>

<template>
    <div v-if="!secret">
        <BaseCard class="min-h-receipt-card-skeleton">
            <BaseLoader />
        </BaseCard>
    </div>
    <div v-else>
        <BaseCard>
            <section class="form">
                <BaseMessage v-if="secret.is_revealed" type="success"> Secret has been revealed on {{ formatDate(secret.revealed_at) }}. </BaseMessage>
                <BaseMessage v-else-if="secret.is_expired" type="warning">Secret has expired without being revealed.</BaseMessage>
                <BaseMessage v-else type="info">Secret is not yet revealed. It is available until {{ formatDate(secret.expires_at) }}.</BaseMessage>
            </section>

            <section
                :class="[
                    'border-t-2 border-zinc-200 bg-zinc-100 p-4 dark:border-zinc-700 dark:bg-zinc-800',
                    !secret.is_available || !hasAccessToken ? 'rounded-b-sm' : '',
                ]"
            >
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

            <section v-if="secret.is_available && hasAccessToken" class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                <div class="flex flex-col gap-2">
                    <BaseLabel for="secret-link-input">Secret Link</BaseLabel>
                    <BaseInput
                        id="secret-link-input"
                        ref="secret-link-input"
                        data-test="secret-link-input"
                        :value="secretUrl"
                        readonly
                        @click="selectSecretLinkInput"
                    />
                </div>

                <BaseMessage type="warning">You will only see this link once.</BaseMessage>
            </section>

            <template v-if="secret.is_available && hasAccessToken" #actions>
                <BaseInput
                    v-if="secret.is_passphrase_protected && showPassphraseInput"
                    id="passphrase-input"
                    ref="passphrase-input"
                    data-test="passphrase-input"
                    type="password"
                    v-model="passphrase"
                    :disabled="isDeletingSecret"
                    placeholder="Enter passphrase..."
                    @keyup.enter="handleSecretDelete"
                    @keyup.esc="cancelSecretDeletion"
                />

                <BaseButton v-if="!showPassphraseInput" icon-before="fa-solid fa-copy" @click="copySecretUrl">Copy Secret Link</BaseButton>

                <BaseButton
                    type="danger"
                    icon-before="fa-solid fa-trash"
                    :disabled="isDeletingSecret || (showPassphraseInput && !passphrase)"
                    @click="handleSecretDelete"
                >
                    Delete Secret
                </BaseButton>
                <BaseButton v-if="showPassphraseInput" type="outline-secondary" :disabled="isDeletingSecret" @click="cancelSecretDeletion">Cancel</BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
