<script setup>
import { ref, computed, watch, onMounted, onUnmounted, useTemplateRef } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { echo } from '@laravel/echo-vue';
import { DateTime } from 'luxon';
import { useNow } from '@/composables/useNow';
import { getSecret, deleteSecret } from '@/api';
import { useNotificationStore } from '@/stores/notifications';
import { useClipboard } from '@/composables/useClipboard';
import { useElementFocus } from '@/composables/useElementFocus';
import { useSecretExpirationProgress } from '@/composables/useSecretExpirationProgress';
import formatDate from '@/utils/formatDate';
import BaseAlert from '@/components/BaseAlert.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLabel from '@/components/BaseLabel.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseProgressBar from '@/components/BaseProgressBar.vue';
import SecretPreview from '@/components/SecretPreview.vue';

const route = useRoute();
const router = useRouter();
const notify = useNotificationStore();
const { now } = useNow();
const { copyToClipboard } = useClipboard();
const { focus, focusAndSelect } = useElementFocus();

const secretLinkInput = useTemplateRef('secret-link-input');
const passphraseInput = useTemplateRef('passphrase-input');

const secret = ref(null);
const secretAccessToken = ref(null);
const passphrase = ref('');
const showPassphraseInput = ref(false);
const isDeletingSecret = ref(false);

const hasAccessToken = computed(() => {
    return !!secretAccessToken.value;
});

const expiresInDiffForHumans = computed(() => {
    if (!secret.value?.expires_at) return '';

    now.value;

    return DateTime.fromISO(secret.value.expires_at).toRelative({ unit: ['days', 'hours', 'minutes', 'seconds'] });
});

const secretUrl = computed(() => {
    if (!hasAccessToken.value) return '';

    const path = router.resolve({
        name: 'secret',
        params: {
            id: secret.value.id,
        },
        hash: `#${secretAccessToken.value}`,
    }).href;

    return `${window.location.origin}${path}`;
});

const fetchSecret = async () => {
    const state = window.history.state;

    // Retrieve secret from history state (passed during redirect)
    // and immediately clear it to prevent access via browser navigation
    if (state && state.secret) {
        secret.value = state.secret;
        secretAccessToken.value = secret.value.access_token;

        // Clear the state in the browser history
        const newState = { ...state };
        delete newState.secret;
        window.history.replaceState(newState, '');

        return;
    }

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
            access_token: secretAccessToken.value,
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

const handleSecretIdChange = (newId, oldId) => {
    resetPage();
    fetchSecret();

    if (oldId) echo().leave(`secrets.${oldId}`);

    if (!newId) return;

    echo()
        .channel(`secrets.${newId}`)
        .listen('.secret.revealed', (e) => {
            secret.value = e.secret;
        });
};

const resetPage = () => {
    secret.value = null;
    secretAccessToken.value = null;
    clearPassphraseInput();
    isDeletingSecret.value = false;
};

const handlePageShow = (event) => {
    if (event.persisted) fetchSecret();
};

const secretExpirationProgress = useSecretExpirationProgress(secret, fetchSecret);

watch(showPassphraseInput, handlePassphraseInputVisibilityChange, { flush: 'post' });

watch(() => route.params.id, handleSecretIdChange, { immediate: true });

watch(secretLinkInput, selectSecretLinkInput, { once: true });

onMounted(() => {
    window.addEventListener('pageshow', handlePageShow);
});

onUnmounted(() => {
    if (secret.value?.id) echo().leave(`secrets.${secret.value.id}`);

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
                <BaseAlert v-if="secret.is_revealed" type="success">
                    Secret has been revealed on {{ formatDate(secret.revealed_at) }}.
                </BaseAlert>
                <BaseAlert v-else-if="secret.is_expired" type="warning">Secret has expired without being revealed.</BaseAlert>
                <BaseAlert v-else type="info">
                    Secret is not yet revealed. It is available until {{ formatDate(secret.expires_at) }}.
                </BaseAlert>
            </section>

            <section
                class="border-t-2 border-zinc-200 bg-zinc-75 p-4 dark:border-zinc-700 dark:bg-zinc-800"
                :class="{ 'rounded-b-sm': !secret.is_available || !hasAccessToken }"
            >
                <SecretPreview :passphrase-protected="secret.is_passphrase_protected" />
            </section>

            <section v-if="!secret.is_revealed && !secret.is_expired" class="border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                <BaseProgressBar
                    type="expiration"
                    :label="`${secretExpirationProgress >= 100 ? 'Expired' : 'Expires'} ${expiresInDiffForHumans}`"
                    :value="secretExpirationProgress"
                />
            </section>

            <section v-if="secret.is_available && hasAccessToken" class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                <div class="form-group">
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

                <BaseAlert type="warning">You will only see this link once.</BaseAlert>
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
                    :loading="isDeletingSecret"
                    @click="handleSecretDelete"
                >
                    Delete Secret
                </BaseButton>
                <BaseButton v-if="showPassphraseInput" type="light" :disabled="isDeletingSecret" @click="cancelSecretDeletion">
                    Cancel
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
