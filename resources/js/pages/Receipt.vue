<script setup>
import { ref, computed, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseMessage from '@/components/BaseMessage.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLabel from '@/components/BaseLabel.vue';

const route = useRoute();
const router = useRouter();

const secret = ref(null);
const passphrase = ref('');
const isShowingPassphraseInput = ref(false);

watchEffect(async () => {
    const key = route.params.key;

    try {
        const response = await axios.get(`/api/receipts/${key}`);
        secret.value = response.data.data;
    } catch (e) {
        router.push({ name: 'home' });
    }
});

const secretUrl = computed(() => {
    const path = router.resolve({
        name: 'secret',
        params: { secretKey: secret.value.secret_key }
    }).href;

    return `${window.location.origin}${path}`;
});

const handleCopyUrlButtonClick = () => {
    navigator.clipboard.writeText(secretUrl.value);
}

const hasActions = computed(() => {
    return !secret.value.is_revealed && !secret.value.is_expired;
});

const handleDeleteSecretButtonClick = async () => {
    // We need to show the passphrase input first if the secret is passphrase-protected
    if (secret.value.is_passphrase_protected && !isShowingPassphraseInput.value) {
        isShowingPassphraseInput.value = true;
        return;
    }

    try {
        await axios.delete(`/api/secrets/${secret.value.secret_key}`, {
            data: { passphrase: passphrase.value }
        });

        router.push({ name: 'home' });
    } catch (e) {
        console.error(e);
    }
}
</script>

<template>
    <div v-if="!secret">
        <BaseLoader />
    </div>
    <div v-else>
        <BaseCard>
            <section class="p-4">
                <BaseMessage v-if="secret.is_revealed" type="success">
                    Your secret has been revealed.
                </BaseMessage>
                <BaseMessage v-else-if="secret.is_expired" type="warning">
                    Your secret has expired without being revealed.
                </BaseMessage>
                <BaseMessage v-else-if="secret.expires_at" type="info">
                    Your secret is available until {{ new Date(secret.expires_at).toLocaleString() }}.
                </BaseMessage>
                <BaseMessage v-else type="info">
                    Your secret is available and will never expire.
                </BaseMessage>
            </section>

            <section
                class="border-y-2 border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-900 p-4"
            >
                <div class="flex items-center justify-between py-2 font-mono gap-2 select-none">
                    <div class="flex flex-1 items-center min-w-0">
                        <span class="truncate blur-sm text-black dark:text-white">
                            ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••
                        </span>
                    </div>
                    <div class="flex flex-row gap-2 items-end shrink-0 text-zinc-400 dark:text-zinc-500">
                        <span class="flex items-center gap-1 rounded-full bg-zinc-200 px-2 py-1 text-xs font-medium dark:bg-zinc-700">
                            <i class="fa-solid fa-lock"></i>
                            Encrypted
                        </span>
                        <span v-if="secret.is_passphrase_protected" class="flex items-center gap-1 rounded-full bg-zinc-200 px-2 py-1 text-xs font-medium dark:bg-zinc-700">
                            <i class="fa-solid fa-key"></i>
                            Passphrase-protected
                        </span>
                    </div>
                </div>
            </section>

            <section class="p-4">
                <div class="flex flex-col gap-2">
                    <BaseLabel>Secret Link</BaseLabel>
                    <BaseInput :value="secretUrl" readonly @click="$event.target.select()" />
                </div>
            </section>

            <template v-if="hasActions" #actions>
                <BaseInput v-if="secret.is_passphrase_protected && isShowingPassphraseInput" type="password" v-model="passphrase" placeholder="Enter passphrase..." />

                <BaseButton
                    v-if="!isShowingPassphraseInput"
                    type="primary"
                    icon-before="fa-solid fa-copy"
                    @click="handleCopyUrlButtonClick"
                >
                    Copy Secret Link
                </BaseButton>

                <BaseButton
                    type="danger"
                    icon-before="fa-solid fa-trash"
                    :disabled="isShowingPassphraseInput && !passphrase"
                    @click="handleDeleteSecretButtonClick"
                >
                    Delete Secret
                </BaseButton>
                <BaseButton
                    v-if="isShowingPassphraseInput"
                    type="outline-secondary"
                    @click="isShowingPassphraseInput = false"
                >
                    Cancel
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
