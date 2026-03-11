<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useNotificationStore } from '@/stores/notifications';
import sleep from '@/utils/sleep';
import AppLogo from '@/components/AppLogo.vue';
import BaseAlert from '@/components/BaseAlert.vue';
import BaseBadge from '@/components/BaseBadge.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseIconButton from '@/components/BaseIconButton.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseLabel from '@/components/BaseLabel.vue';
import BaseLink from '@/components/BaseLink.vue';
import BaseLoader from '@/components/BaseLoader.vue';
import BaseProgressBar from '@/components/BaseProgressBar.vue';
import BaseSelect from '@/components/BaseSelect.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';
import StatisticGrid from '@/components/StatisticGrid.vue';
import StatisticGridItem from '@/components/StatisticGridItem.vue';

const notify = useNotificationStore();

const rating = ref(0);
const progressValue = ref(0);
const input = ref('Input value');
const textarea = ref('Textarea value');
const iconButtonsToggled = ref(true);
const progressIntervalId = ref(null);
const progressTimeoutId = ref(null);

const appName = import.meta.env.VITE_APP_NAME;

const alertTypes = ['neutral', 'success', 'danger', 'info', 'warning'];

const buttonTypes = ['primary', 'secondary', 'success', 'danger', 'light'];

const iconButtonVariants = [
    { type: 'success', icon: 'fa-solid fa-check' },
    { type: 'danger', icon: 'fa-solid fa-fire' },
    { type: 'info', icon: 'fa-solid fa-paperclip' },
    { type: 'warning', icon: 'fa-solid fa-lightbulb' },
    { type: 'light', icon: 'fa-solid fa-volume-xmark' },
];

const optionalSelectOptions = [
    { value: '', label: 'Option with empty value' },
    { value: 1, label: 'Option with value' },
    { value: 2, label: 'Disabled option with value', disabled: true },
];

const requiredSelectOptions = [
    { value: '', label: 'Option with empty value' },
    { value: 1, label: 'Option with value' },
    { value: 2, label: 'Disabled option with value', disabled: true },
];

const rateMessage = () => {
    if (rating.value === 0) rating.value = 1;
    else if (rating.value === 1) rating.value = -1;
    else rating.value = 0;
};

const increaseProgress = () => {
    progressValue.value += 5; // From 0 to 100 in 4 seconds

    if (progressValue.value >= 100) {
        clearInterval(progressIntervalId.value);
        progressTimeoutId.value = setTimeout(startProgressLoop, 2000);
    }
};

const startProgressLoop = async () => {
    progressValue.value = 0;

    await sleep(1500);

    progressIntervalId.value = setInterval(increaseProgress, 201); // Progress bar transition is 200ms by default
};

onMounted(() => {
    startProgressLoop();
    notify.test();
});

onBeforeUnmount(() => {
    notify.clearTest();

    if (progressIntervalId.value) clearInterval(progressIntervalId.value);
    if (progressTimeoutId.value) clearTimeout(progressTimeoutId.value);
});
</script>

<template>
    <div>
        <div class="my-4">
            <div class="mb-2 text-2xl font-semibold text-title">Take a peek at our UI kit.</div>
            <div class="text-secondary">The building blocks behind {{ appName }} — every component, every state, all in one place.</div>
        </div>
        <div>
            <BaseCard>
                <template #title>Card</template>
                <div class="form">Card content.</div>
            </BaseCard>

            <BaseCard>
                <template #title>Logo</template>
                <div class="form">
                    <AppLogo />
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Typography</template>
                <div class="form">
                    <div class="form-group">
                        <div v-for="type in ['text-logo', 'text-title', 'text-secondary', 'text-muted']" :key="type" :class="type">
                            .{{ type }}
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Alert</template>
                <div class="form">
                    <BaseAlert v-for="type in alertTypes" :key="type" :type="type">
                        {{ type }}
                    </BaseAlert>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <BaseAlert v-for="type in alertTypes" :key="type" :type="type" :dismissible="true">
                        {{ type }}
                    </BaseAlert>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Buttons</template>
                <div class="form">
                    <div class="form-group">
                        <BaseLabel>Default buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton v-for="type in buttonTypes" :key="type" :type="type">
                                {{ type }}
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Disabled buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton v-for="type in buttonTypes" :key="type" :type="type" disabled>
                                {{ type }}
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Disabled buttons with spinner</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton v-for="type in buttonTypes" :key="type" :type="type" :loading="true" disabled>
                                {{ type }}
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Buttons with icons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton icon-before="fa-solid fa-lock">Create Link</BaseButton>
                            <BaseButton icon-after="fa-solid fa-paper-plane">Send Message</BaseButton>
                            <BaseButton
                                :icon-before="rating === 1 ? 'fa-solid fa-thumbs-up' : 'fa-regular fa-thumbs-up'"
                                :icon-after="rating === -1 ? 'fa-solid fa-thumbs-down' : 'fa-regular fa-thumbs-down'"
                                :type="rating === 1 ? 'success' : rating === -1 ? 'danger' : 'secondary'"
                                @click="rateMessage"
                            >
                                Rate Message
                            </BaseButton>
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Icon buttons</template>
                <div class="form">
                    <div class="form-group">
                        <BaseLabel>Default buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseIconButton
                                v-for="button in iconButtonVariants"
                                :key="button.type"
                                :type="button.type"
                                :icon="button.icon"
                            />
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Disabled buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseIconButton
                                v-for="button in iconButtonVariants"
                                :key="button.type"
                                :type="button.type"
                                :icon="button.icon"
                                disabled
                            />
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Colored toggleable buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseIconButton
                                v-for="button in iconButtonVariants"
                                :key="button.type"
                                :type="button.type"
                                :icon="iconButtonsToggled ? 'fa-solid fa-thumbs-up' : 'fa-regular fa-thumbs-up'"
                                :colored="true"
                                :active="iconButtonsToggled"
                                @click="iconButtonsToggled = !iconButtonsToggled"
                            />
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Small colored buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseIconButton
                                v-for="button in iconButtonVariants"
                                :key="button.type"
                                :type="button.type"
                                icon="fa-solid fa-xmark"
                                :colored="true"
                                size="sm"
                            />
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Links</template>
                <div class="form">
                    <div>
                        <BaseLink :to="{ to: '/' }">Scroll to top</BaseLink>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Badges</template>
                <div class="form">
                    <div class="flex flex-wrap items-start gap-2">
                        <BaseBadge>Default badge</BaseBadge>
                        <BaseBadge icon="fa-solid fa-lock">Encrypted</BaseBadge>
                        <BaseBadge icon="fa-solid fa-key">Passphrase-protected</BaseBadge>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Labels</template>
                <div class="form">
                    <div class="form-group">
                        <BaseLabel>Optional label</BaseLabel>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel required>Required label</BaseLabel>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Inputs</template>
                <div class="form">
                    <div class="form-row">
                        <div class="form-group">
                            <BaseLabel>Default input</BaseLabel>
                            <BaseInput placeholder="Placeholder" v-model="input" />
                        </div>

                        <div class="form-group">
                            <BaseLabel>Read-only input</BaseLabel>
                            <BaseInput placeholder="Placeholder" v-model="input" readonly />
                        </div>

                        <div class="form-group">
                            <BaseLabel>Disabled input</BaseLabel>
                            <BaseInput placeholder="Placeholder" v-model="input" disabled />
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Textareas</template>
                <div class="form">
                    <div class="form-row">
                        <div class="form-group">
                            <BaseLabel>Default textarea</BaseLabel>
                            <BaseTextarea placeholder="Placeholder" v-model="textarea" />
                        </div>

                        <div class="form-group">
                            <BaseLabel>Read-only textarea</BaseLabel>
                            <BaseTextarea placeholder="Placeholder" v-model="textarea" readonly />
                        </div>

                        <div class="form-group">
                            <BaseLabel>Disabled textarea</BaseLabel>
                            <BaseTextarea placeholder="Placeholder" v-model="textarea" disabled />
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Selects</template>
                <div class="form">
                    <div class="form-row">
                        <div class="form-group">
                            <BaseLabel>Optional select</BaseLabel>
                            <BaseSelect>
                                <option
                                    v-for="option in optionalSelectOptions"
                                    :key="option.value"
                                    :value="option.value"
                                    :disabled="option.disabled"
                                >
                                    {{ option.label }}
                                </option>
                            </BaseSelect>
                        </div>
                        <div class="form-group">
                            <BaseLabel required>Required select</BaseLabel>
                            <BaseSelect required>
                                <option
                                    v-for="option in requiredSelectOptions"
                                    :key="option.value"
                                    :value="option.value"
                                    :disabled="option.disabled"
                                >
                                    {{ option.label }}
                                </option>
                            </BaseSelect>
                        </div>
                        <div class="form-group">
                            <BaseLabel>Disabled select</BaseLabel>
                            <BaseSelect disabled>
                                <option
                                    v-for="option in optionalSelectOptions"
                                    :key="option.value"
                                    :value="option.value"
                                    :disabled="option.disabled"
                                >
                                    {{ option.label }}
                                </option>
                            </BaseSelect>
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Loaders & spinners</template>
                <div class="form">
                    <BaseLoader padding="" />
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <BaseSpinner />
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Progress bars</template>
                <div class="form">
                    <BaseProgressBar :value="progressValue" />
                    <BaseProgressBar type="success" :value="progressValue" />
                    <BaseProgressBar type="danger" :value="progressValue" />
                    <BaseProgressBar type="info" :value="progressValue" />
                    <BaseProgressBar type="warning" :value="progressValue" />
                    <BaseProgressBar type="expiration" :value="progressValue" />
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <BaseProgressBar
                        type="info"
                        :value="progressValue"
                        label="Progress bar with label"
                        :value-label="progressValue + '%'"
                    />
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Statistics</template>
                <div class="p-4">
                    <StatisticGrid class="sm:grid-cols-2 lg:grid-cols-4">
                        <StatisticGridItem title="Secrets created" icon="fa-solid fa-lock" :value="progressValue * 10000" />
                        <StatisticGridItem
                            title="Secrets revealed"
                            icon="fa-solid fa-unlock"
                            icon-bg="bg-emerald-500"
                            :value="progressValue * 7500"
                        />
                        <StatisticGridItem
                            title="Secrets expired"
                            icon="fa-solid fa-hourglass"
                            icon-bg="bg-yellow-500"
                            :value="progressValue * 1500"
                        />
                        <StatisticGridItem
                            title="Secrets burned"
                            icon="fa-solid fa-fire"
                            icon-bg="bg-rose-500"
                            :value="progressValue * 1000"
                        />
                    </StatisticGrid>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Toast notifications</template>
                <div class="form">
                    <div class="flex flex-wrap items-start gap-2">
                        <BaseButton @click="notify.test()">Show</BaseButton>
                        <BaseButton @click="notify.clearTest()">Hide</BaseButton>
                    </div>
                </div>
            </BaseCard>
        </div>
    </div>
</template>
