<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { useClipboard } from '@/composables/useClipboard';
import { useElementFocus } from '@/composables/useElementFocus';
import axios from '@/axios';
import BaseCard from '@/components/BaseCard.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseMessage from '@/components/BaseMessage.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';

const route = useRoute();
const router = useRouter();
const toast = useToast();
const { copyToClipboard } = useClipboard();
const { focus } = useElementFocus();

const passphraseInput = ref(null);

const secret = ref(null);
const secretContent = ref(null);
const passphrase = ref('');
const isRevealingSecret = ref(false);

const fetchSecret = async () => {
    resetPage();

    try {
        const response = await axios.get(`/api/secrets/${route.params.accessToken}`);
        secret.value = response.data.secret;

        // Auto-focus the passphrase input if the secret is passphrase-protected
        if (secret.value.is_passphrase_protected) {
            focusPassphraseInput();
        }
    } catch (error) {
        router.replace({ name: 'home' });
    }
};

const revealSecret = async () => {
    if (isRevealingSecret.value) return;

    isRevealingSecret.value = true;

    try {
        const response = await axios.post(`/api/secrets/${route.params.accessToken}/reveal`, {
            passphrase: passphrase.value,
        });

        secretContent.value = response.data.content;

        toast.success('Secret has been revealed.');
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
    copyToClipboard(secretContent.value, 'Secret message copied!', 'Failed to copy secret message.');
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

watch(() => route.params.accessToken, fetchSecret, { immediate: true });

onMounted(() => {
    window.addEventListener('pageshow', handlePageShow);
});

onUnmounted(() => {
    window.removeEventListener('pageshow', handlePageShow);
});
</script>

<template>
    <div v-if="!secret" class="my-4">
        <BaseLoader />
    </div>
    <div v-else class="my-4">
        <BaseCard v-if="secretContent">
            <section class="p-4">
                <BaseMessage type="info">This secret message has been deleted from our servers. You can close this window when done.</BaseMessage>
            </section>

            <section class="border-t-2 border-zinc-200 bg-zinc-100 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                <BaseTextarea data-test="secret-content" v-model="secretContent" rows="7" readonly />
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
                    <div class="flex shrink-0 flex-row items-end gap-2 text-zinc-400 dark:text-zinc-500">
                        <span class="flex items-center gap-1 rounded-full bg-zinc-200 px-2 py-1 text-xs font-medium dark:bg-zinc-700">
                            <i class="fa-solid fa-lock"></i>
                            Encrypted
                        </span>
                        <span
                            v-if="secret.is_passphrase_protected"
                            class="flex items-center gap-1 rounded-full bg-zinc-200 px-2 py-1 text-xs font-medium dark:bg-zinc-700"
                        >
                            <i class="fa-solid fa-key"></i>
                            Passphrase-protected
                        </span>
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
                    @keyup.enter="revealSecret"
                />

                <BaseButton
                    data-test="reveal-secret-btn"
                    type="primary"
                    :disabled="isRevealingSecret || (secret.is_passphrase_protected && !passphrase)"
                    @click="revealSecret"
                >
                    Reveal Secret
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
