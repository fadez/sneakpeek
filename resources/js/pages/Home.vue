<script setup>
import { ref, computed, useTemplateRef, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { storeSecret } from '@/api';
import { useElementFocus } from '@/composables/useElementFocus';
import { useFeatureStore } from '@/stores/features';
import { useNotificationStore } from '@/stores/notifications';
import BaseAlert from '@/components/BaseAlert.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLabel from '@/components/BaseLabel.vue';
import BaseSelect from '@/components/BaseSelect.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';

const features = useFeatureStore();
const router = useRouter();
const notify = useNotificationStore();
const { focus } = useElementFocus();

const secretContentTextarea = useTemplateRef('secret-content-textarea');

const content = ref('');
const ttl = ref(86400); // 1 day by default
const passphrase = ref('');
const isCreatingSecret = ref(false);

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

const canCreateSecret = computed(() => !!content.value.trim() && !!ttl.value);

const createSecret = async () => {
    if (!canCreateSecret.value) return;

    isCreatingSecret.value = true;

    try {
        const secret = await storeSecret({
            content: content.value,
            ttl: ttl.value,
            passphrase: passphrase.value,
        });

        notify.secretCreated();

        router.push({
            name: 'receipt',
            params: { id: secret.id },
            state: { secret: secret },
        });
    } catch (error) {
        //
    } finally {
        isCreatingSecret.value = false;
    }
};

onMounted(() => {
    focus(secretContentTextarea);
});
</script>

<template>
    <div>
        <div class="my-4">
            <div class="mb-2 text-2xl font-semibold text-title">Paste a password, secret message or private link below.</div>
            <div class="text-secondary">Keep sensitive data out of your messages or inbox.</div>
        </div>
        <BaseCard>
            <div class="form">
                <BaseAlert
                    v-if="features.isActive('lucky-message')"
                    type="success"
                    icon="fa-solid fa-trophy"
                    dismissible
                    @dismiss="features.deactivate('lucky-message')"
                >
                    Only 1 in 100 visitors see this special message. You're one of them!
                </BaseAlert>

                <BaseAlert type="info">Your message will self-destruct after being revealed.</BaseAlert>

                <div class="form-group">
                    <BaseLabel
                        for="secret-content-textarea"
                        required
                    >
                        Content
                    </BaseLabel>
                    <BaseTextarea
                        id="secret-content-textarea"
                        ref="secret-content-textarea"
                        data-test="secret-content-textarea"
                        placeholder="Your secret content goes here"
                        class="font-mono"
                        rows="7"
                        maxlength="10000"
                        v-model="content"
                        @keydown.meta.enter.exact.prevent="createSecret"
                        @keydown.ctrl.enter.exact.prevent="createSecret"
                    />
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <BaseLabel for="passphrase-input">Passphrase</BaseLabel>
                        <BaseInput
                            id="passphrase-input"
                            data-test="passphrase-input"
                            type="password"
                            placeholder="Enter a passphrase"
                            maxlength="255"
                            autocomplete="off"
                            v-model="passphrase"
                            @keydown.meta.enter.exact.prevent="createSecret"
                            @keydown.ctrl.enter.exact.prevent="createSecret"
                        />
                    </div>

                    <div class="form-group">
                        <BaseLabel
                            for="ttl-select"
                            required
                        >
                            Expiration Time
                        </BaseLabel>
                        <BaseSelect
                            id="ttl-select"
                            data-test="ttl-select"
                            v-model.number="ttl"
                            required
                        >
                            <option
                                v-for="option in ttlOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </BaseSelect>
                    </div>
                </div>
            </div>

            <template #actions>
                <BaseButton
                    data-test="create-secret-btn"
                    icon-before="fa-solid fa-lock"
                    :disabled="!canCreateSecret || isCreatingSecret"
                    :loading="isCreatingSecret"
                    @click="createSecret"
                >
                    Create Link
                </BaseButton>
            </template>
        </BaseCard>
    </div>
</template>
