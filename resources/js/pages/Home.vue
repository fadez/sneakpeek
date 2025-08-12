<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLabel from '@/components/BaseLabel.vue';
import BaseMessage from '@/components/BaseMessage.vue';
import BaseSelect from '@/components/BaseSelect.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';

const router = useRouter();

const isLoading = ref(false);
const content = ref('');
const ttl = ref('2592000'); // 30 days by default
const passphrase = ref('');

const handleCreateLinkButtonClick = async () => {
    isLoading.value = true;

    const response = await axios.post('/api/secrets', {
        content: content.value,
        ttl: ttl.value,
        passphrase: passphrase.value,
    });

    isLoading.value = false;

    router.push({
        name: 'receipt',
        params: { key: response.data.data.key },
        state: { secret: response.data.data },
        replace: true,
    });
}
</script>

<template>
    <div>
        <div class="text-center mb-4 sm:my-8">
            <div class="mb-2 font-semibold text-2xl text-zinc-600 dark:text-zinc-100">
                Paste a password, secret message or private link below.
            </div>
            <div class="text-zinc-600 dark:text-zinc-300">
                Keep sensitive data out of your messages or inbox.
            </div>
        </div>
        <BaseCard>
            <div class="p-4 grid grid-cols-1 gap-4">
                <div class="flex flex-col gap-2">
                    <BaseLabel :required="true">Content</BaseLabel>
                    <BaseTextarea placeholder="Secret content goes here..." rows="7" maxlength="10000" v-model="content"></BaseTextarea>
                </div>

                <div class="flex flex-col gap-2">
                    <BaseLabel>Passphrase</BaseLabel>
                    <BaseInput type="password" placeholder="Enter a passphrase..." maxlength="255" autocomplete="off" v-model="passphrase"></BaseInput>
                </div>

                <div class="flex flex-col gap-2">
                    <BaseLabel :required="true">Expiration Time</BaseLabel>
                    <BaseSelect v-model="ttl">
                        <option value="60">Expires in 1 minute</option>
                        <option value="180">Expires in 30 minutes</option>
                        <option value="3600">Expires in 1 hour</option>
                        <option value="43200">Expires in 12 hours</option>
                        <option value="86400">Expires in 1 day</option>
                        <option value="259200">Expires in 3 days</option>
                        <option value="604800">Expires in 7 days</option>
                        <option value="2592000">Expires in 30 days</option>
                        <option value="7776000">Expires in 90 days</option>
                        <option value="">Never expires</option>
                    </BaseSelect>
                </div>

                <BaseMessage v-if="!ttl" type="warning">
                    Without an expiration, the secret will remain on our servers until it's revealed.
                </BaseMessage>

                <BaseMessage type="info">
                    Your message will self-destruct after being revealed.
                </BaseMessage>
            </div>

            <template #actions>
                <BaseButton
                    icon-before="fa-solid fa-lock"
                    :disabled="!content.trim() || isLoading"
                    @click="handleCreateLinkButtonClick"
                >
                    Create Link
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
