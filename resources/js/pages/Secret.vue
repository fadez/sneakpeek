<script setup>
import { useRoute, useRouter } from 'vue-router';
import { ref, watchEffect } from 'vue';
import axios from 'axios';
import BaseTextarea from '@/components/BaseTextarea.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseMessage from '@/components/BaseMessage.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseInput from '@/components/BaseInput.vue';

const route = useRoute();
const router = useRouter();
const secret = ref(null);
const secretContent = ref(null);
const passphrase = ref('');
const isLoading = ref(false);

watchEffect(async () => {
    try {
        const response = await axios.get(`/api/secrets/${route.params.secretKey}`);
        secret.value = response.data.data;
    } catch (e) {
        console.error(e);
        router.push({ name: 'home' });
    }
});

const handleRevealSecretButtonClick = async () => {
    isLoading.value = true;

    try {
        const response = await axios.post(`/api/secrets/${route.params.secretKey}/reveal`, {
            passphrase: passphrase.value,
        });
        secretContent.value = response.data.content;
    } catch (e) {
        console.error(e);
    } finally {
        isLoading.value = false;
    }
};

const handleCopySecretButtonClick = () => {
    navigator.clipboard.writeText(secretContent.value);
};
</script>

<template>
    <div v-if="!secret" class="my-4">
        <BaseLoader />
    </div>
    <div v-else class="my-4">
        <BaseCard v-if="secretContent">
            <section class="p-4">
                <BaseMessage type="info">
                    You can close this window when done.
                </BaseMessage>
            </section>

            <section class="border-t-2 border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-900 p-4">
                <BaseTextarea v-model="secretContent" rows="7" readonly />
            </section>

            <template #actions>
                <BaseButton
                    type="primary"
                    icon-before="fa-solid fa-copy"
                    @click="handleCopySecretButtonClick"
                >
                    Copy to Clipboard
                </BaseButton>
            </template>
        </BaseCard>
        <BaseCard v-else>
            <section class="p-4">
                <BaseMessage type="info">
                    Your secret message is ready, we will only show it once when you click the button.
                </BaseMessage>
            </section>

            <section class="border-t-2 border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-900 p-4">
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

            <template #actions>
                <BaseLoader size="w-13 h-13" padding="p-0" v-if="isLoading" />

                <BaseInput v-if="secret.is_passphrase_protected && !isLoading" type="password" v-model="passphrase" placeholder="Enter passphrase..." />

                <BaseButton
                    v-if="!isLoading"
                    type="primary"
                    :disabled="secret.is_passphrase_protected && !passphrase"
                    @click="handleRevealSecretButtonClick"
                >
                    Reveal Secret Message
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
