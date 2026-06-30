<script setup lang="ts">
import { ref, computed, useTemplateRef, onMounted, Ref } from 'vue';
import { useRouter } from 'vue-router';
import { SECRET_TTL_OPTIONS } from '@/constants';
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

const secretContentTextarea = useTemplateRef('secret-content-textarea') as Ref<HTMLTextAreaElement | null>;

const content = ref('');
const ttl = ref(86400);
const passphrase = ref('');
const isCreatingSecret = ref(false);

const canCreateSecret = computed(() => content.value.trim().length > 0 && ttl.value > 0);

const createSecret = async (): Promise<void> => {
    if (!canCreateSecret.value) return;

    isCreatingSecret.value = true;

    try {
        const secret = await storeSecret({
            content: content.value,
            ttl: ttl.value,
            passphrase: passphrase.value,
        });

        notify.secretCreated();

        await router.push({
            name: 'receipt',
            params: { id: secret.id },
            state: { secret: JSON.stringify(secret) },
        });
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

                <BaseAlert type="info">Your message will be removed from our database after being revealed.</BaseAlert>

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
                                v-for="option in SECRET_TTL_OPTIONS"
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
