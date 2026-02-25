<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useNotificationStore } from '@/stores/notifications';
import { useElementFocus } from '@/composables/useElementFocus';
import axios from '@/axios';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLabel from '@/components/BaseLabel.vue';
import BaseMessage from '@/components/BaseMessage.vue';
import BaseSelect from '@/components/BaseSelect.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';

const ttlOptions = [
    { value: 60, label: 'Expires in 1 minute' },
    { value: 300, label: 'Expires in 5 minutes' },
    { value: 1800, label: 'Expires in 30 minutes' },
    { value: 3600, label: 'Expires in 1 hour' },
    { value: 43200, label: 'Expires in 12 hours' },
    { value: 86400, label: 'Expires in 1 day' },
    { value: 259200, label: 'Expires in 3 days' },
    { value: 604800, label: 'Expires in 7 days' },
    { value: 2592000, label: 'Expires in 30 days' },
    { value: 7776000, label: 'Expires in 90 days' },
];

const router = useRouter();
const notify = useNotificationStore();
const { focus } = useElementFocus();

const contentInput = ref(null);

const content = ref('');
const ttl = ref(86400); // 1 day by default
const passphrase = ref('');
const isLoading = ref(false);

const canCreateSecret = computed(() => !!content.value.trim() && !!ttl.value);

const createSecret = async () => {
    if (!canCreateSecret.value) return;

    isLoading.value = true;

    try {
        const response = await axios.post('/api/secrets', {
            content: content.value,
            ttl: ttl.value,
            passphrase: passphrase.value,
        });

        notify.secretCreated();

        router.push({
            name: 'receipt',
            params: { id: response.data.secret.id },
            state: { secret: response.data.secret },
        });
    } catch (error) {
        //
    } finally {
        isLoading.value = false;
    }
};

const focusContentInput = () => {
    focus(contentInput);
};

onMounted(focusContentInput);
</script>

<template>
    <div>
        <div class="mb-4 text-center sm:my-8">
            <div class="text-title mb-2 text-2xl font-semibold">Paste a password, secret message or private link below.</div>
            <div class="text-subtitle">Keep sensitive data out of your messages or inbox.</div>
        </div>
        <BaseCard>
            <div class="form">
                <div class="flex flex-col gap-2">
                    <BaseLabel for="content" :required="true">Content</BaseLabel>
                    <BaseTextarea
                        ref="contentInput"
                        id="content"
                        data-test="content-input"
                        placeholder="Secret content goes here..."
                        rows="7"
                        maxlength="10000"
                        v-model="content"
                        @keydown.meta.enter.exact.prevent="createSecret"
                        @keydown.ctrl.enter.exact.prevent="createSecret"
                    ></BaseTextarea>
                </div>

                <div class="flex flex-col gap-2">
                    <BaseLabel for="passphrase">Passphrase</BaseLabel>
                    <BaseInput
                        id="passphrase"
                        data-test="passphrase-input"
                        type="password"
                        placeholder="Enter a passphrase..."
                        maxlength="255"
                        autocomplete="off"
                        v-model="passphrase"
                        @keydown.meta.enter.exact.prevent="createSecret"
                        @keydown.ctrl.enter.exact.prevent="createSecret"
                    ></BaseInput>
                </div>

                <div class="flex flex-col gap-2">
                    <BaseLabel for="ttl" :required="true">Expiration Time</BaseLabel>
                    <BaseSelect id="ttl" v-model="ttl" required>
                        <option v-for="option in ttlOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </BaseSelect>
                </div>

                <BaseMessage type="info">Your message will self-destruct after being revealed.</BaseMessage>
            </div>

            <template #actions>
                <BaseButton data-test="submit-btn" icon-before="fa-solid fa-lock" :disabled="!canCreateSecret || isLoading" @click="createSecret">
                    Create Link
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
