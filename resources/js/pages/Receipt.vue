<script setup lang="ts">
import type { Secret, SecretWithAccessToken } from '@/types';
import type { Ref } from 'vue';
import { ref, computed, watch, onMounted, onBeforeUnmount, useTemplateRef } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { echo } from '@laravel/echo-vue';
import { DateTime } from 'luxon';
import { getSecret, burnSecret } from '@/api';
import { useNotificationStore } from '@/stores/notifications';
import { useClipboard } from '@/composables/useClipboard';
import { useElementFocus } from '@/composables/useElementFocus';
import { useSecretExpirationProgress } from '@/composables/useSecretExpirationProgress';
import { useNow } from '@/composables/useNow';
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

const secretLinkInput = useTemplateRef('secret-link-input') as Ref<HTMLInputElement | null>;
const passphraseInput = useTemplateRef('passphrase-input') as Ref<HTMLInputElement | null>;

const accessToken = ref<string | undefined>();
const secret = ref<Secret | SecretWithAccessToken | null>(null);
const passphrase = ref<string>('');
const showPassphraseInput = ref<boolean>(false);
const isDeletingSecret = ref<boolean>(false);

const hasAccessToken = computed<boolean>(() => {
    return !!accessToken.value;
});

const expiresInDiffForHumans = computed<string>(() => {
    if (!secret.value?.expires_at) return '';

    void now.value;

    return (
        DateTime.fromISO(secret.value.expires_at).toRelative({
            unit: ['days', 'hours', 'minutes', 'seconds'],
        }) ?? ''
    );
});

/*
The access token is placed after the "#" fragment in the URL for these security reasons:
- The hash fragment is never sent to the server.
- Prevents token logging in server, proxy, and analytics logs.
- Prevents accidental leakage via HTTP Referer headers.
*/
const secretUrl = computed<string>(() => {
    if (!hasAccessToken.value || !secret.value) return '';

    const path = router.resolve({
        name: 'secret',
        params: {
            id: secret.value.id,
        },
        hash: `#${accessToken.value}`,
    }).href;

    return `${window.location.origin}${path}`;
});

const fetchSecret = async (): Promise<void> => {
    const state = window.history.state as any;

    // Retrieve secret from history state (passed during redirect)
    // and immediately clear it to prevent access via browser navigation
    if (typeof state?.secret === 'string') {
        const secretFromState = JSON.parse(state.secret) as SecretWithAccessToken;

        secret.value = secretFromState;
        accessToken.value = secretFromState.access_token;

        const newState = { ...state };
        delete newState.secret;
        window.history.replaceState(newState, '');

        return;
    }

    try {
        secret.value = await getSecret(route.params.id as string);
    } catch {
        await router.replace({ name: 'home' });
    }
};

const deleteSecret = async (): Promise<void> => {
    if (isDeletingSecret.value || !secret.value) return;

    if (secret.value.is_passphrase_protected && !showPassphraseInput.value) {
        showPassphraseInput.value = true;
        return;
    }

    isDeletingSecret.value = true;

    try {
        await burnSecret(secret.value.id, {
            passphrase: passphrase.value,
            access_token: accessToken.value,
        });

        handleSecretBurned();
    } catch {
        clearPassphraseInput();
        focusPassphraseInput();
    } finally {
        isDeletingSecret.value = false;
    }
};

const copySecretUrl = (): void => {
    selectSecretLinkInput();

    copyToClipboard(secretUrl.value, {
        onSuccess: notify.secretLinkCopied,
        onError: notify.failedToCopySecretLink,
    });
};

const cancelSecretDeletion = (): void => {
    clearPassphraseInput();
    hidePassphraseInput();
    selectSecretLinkInput();
};

const selectSecretLinkInput = (): void => {
    focusAndSelect(secretLinkInput);
};

const focusPassphraseInput = (): void => {
    focus(passphraseInput);
};

const clearPassphraseInput = (): void => {
    passphrase.value = '';
};

const hidePassphraseInput = (): void => {
    showPassphraseInput.value = false;
};

const handlePassphraseInputVisibilityChange = (visible: boolean): void => {
    if (visible) focusPassphraseInput();
};

const handleSecretBurned = (): void => {
    notify.secretBurned();

    router.replace({ name: 'home' });
};

const handleSecretIdChange = async (newId: string | undefined, oldId: string | undefined): Promise<void> => {
    resetPage();
    await fetchSecret();

    if (oldId) echo().leave(`secrets.${oldId}`);
    if (!newId) return;

    echo()
        .channel(`secrets.${newId}`)
        .listen('.secret.revealed', (e: { secret: SecretWithAccessToken }) => {
            secret.value = e.secret;
        })
        .listen('.secret.burned', () => {
            handleSecretBurned();
        });
};

const resetPage = (): void => {
    secret.value = null;
    accessToken.value = '';
    isDeletingSecret.value = false;
    clearPassphraseInput();
};

const handlePageShow = (event: PageTransitionEvent): void => {
    if (event.persisted) fetchSecret();
};

const secretExpirationProgress = useSecretExpirationProgress(secret, fetchSecret);

watch(showPassphraseInput, handlePassphraseInputVisibilityChange, {
    flush: 'post',
});

watch(() => route.params.id as string | undefined, handleSecretIdChange, { immediate: true });

watch(secretLinkInput, selectSecretLinkInput, { once: true });

onMounted(() => {
    window.addEventListener('pageshow', handlePageShow);
});

onBeforeUnmount(() => {
    if (secret.value?.id) {
        echo().leave(`secrets.${secret.value.id}`);
    }

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
        <BaseCard :show-actions="secret.is_available && hasAccessToken">
            <section class="form">
                <BaseAlert
                    v-if="secret.is_revealed"
                    type="success"
                >
                    Secret has been revealed on {{ formatDate(secret.revealed_at) }}.
                </BaseAlert>
                <BaseAlert
                    v-else-if="secret.is_expired"
                    type="warning"
                >
                    Secret has expired without being revealed.
                </BaseAlert>
                <BaseAlert
                    v-else
                    type="info"
                >
                    Secret is not yet revealed. It is available until
                    {{ formatDate(secret.expires_at) }}.
                </BaseAlert>
            </section>

            <section
                class="border-t-2 border-zinc-200 bg-zinc-75 p-4 dark:border-zinc-700 dark:bg-zinc-800"
                :class="{ 'rounded-b-sm': !secret.is_available || !hasAccessToken }"
            >
                <SecretPreview :passphrase-protected="secret.is_passphrase_protected" />
            </section>

            <section
                v-if="!secret.is_revealed && !secret.is_expired"
                class="border-t-2 border-zinc-200 p-4 dark:border-zinc-700"
            >
                <BaseProgressBar
                    type="expiration"
                    :label="`${secretExpirationProgress >= 100 ? 'Expired' : 'Expires'} ${expiresInDiffForHumans}`"
                    :value="secretExpirationProgress"
                />
            </section>

            <section
                v-if="secret.is_available && hasAccessToken"
                class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700"
            >
                <div class="form-group">
                    <BaseLabel for="secret-link-input">Secret Link</BaseLabel>
                    <BaseInput
                        id="secret-link-input"
                        ref="secret-link-input"
                        data-test="secret-link-input"
                        v-model="secretUrl"
                        readonly
                        @click="selectSecretLinkInput"
                    />
                </div>

                <BaseAlert type="warning">You will only see this link once.</BaseAlert>
            </section>

            <template #actions>
                <BaseInput
                    v-if="secret.is_passphrase_protected && showPassphraseInput"
                    id="passphrase-input"
                    ref="passphrase-input"
                    data-test="passphrase-input"
                    type="password"
                    v-model="passphrase"
                    :disabled="isDeletingSecret"
                    placeholder="Enter passphrase..."
                    @keyup.enter="deleteSecret"
                    @keyup.esc="cancelSecretDeletion"
                />

                <BaseButton
                    v-if="!showPassphraseInput"
                    icon-before="fa-solid fa-copy"
                    @click="copySecretUrl"
                >
                    Copy Secret Link
                </BaseButton>

                <BaseButton
                    type="danger"
                    icon-before="fa-solid fa-fire"
                    :disabled="isDeletingSecret || (showPassphraseInput && !passphrase)"
                    :loading="isDeletingSecret"
                    @click="deleteSecret"
                >
                    Burn Secret
                </BaseButton>
                <BaseButton
                    v-if="showPassphraseInput"
                    type="light"
                    :disabled="isDeletingSecret"
                    @click="cancelSecretDeletion"
                >
                    Cancel
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
