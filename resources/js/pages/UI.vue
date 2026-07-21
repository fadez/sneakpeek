<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';
import type { ButtonType, IconButtonType, NotificationType, ProgressBarType } from '@/types';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import {
    LucideBellOff,
    LucideBellRing,
    LucideCheck,
    LucideFlame,
    LucideHourglass,
    LucideKeyRound,
    LucideLightbulb,
    LucideLockKeyhole,
    LucideLockKeyholeOpen,
    LucidePaperclip,
    LucideSend,
    LucideThumbsDown,
    LucideThumbsUp,
    LucideVolumeX,
    LucideX,
} from '@lucide/vue';
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

const appName = import.meta.env.VITE_APP_NAME as string;

const rating = ref<number>(0);
const ratingLoading = ref<boolean>(false);
const progressValue = ref<number>(0);
const input = ref<string>('Input value');
const textarea = ref<string>('Textarea value');
const select = ref<string | number>('');
const iconButtonsToggled = ref<boolean>(true);
const progressIntervalId = ref<ReturnType<typeof setInterval> | null>(null);
const progressTimeoutId = ref<ReturnType<typeof setTimeout> | null>(null);

const alertTypes: NotificationType[] = ['neutral', 'success', 'danger', 'info', 'warning'];

const buttonTypes: ButtonType[] = ['primary', 'secondary', 'success', 'danger', 'light'];

const progressBarTypes: ProgressBarType[] = ['default', 'success', 'danger', 'info', 'warning', 'expiration'];

const iconButtonVariants: Array<{ type: IconButtonType; icon: LucideIcon }> = [
    { type: 'success', icon: LucideCheck },
    { type: 'danger', icon: LucideFlame },
    { type: 'info', icon: LucidePaperclip },
    { type: 'warning', icon: LucideLightbulb },
    { type: 'light', icon: LucideVolumeX },
];

const selectOptions: Array<{ value: string | number; label: string; disabled?: boolean }> = [
    { value: '', label: 'Option with empty value' },
    { value: 1, label: 'Option with value' },
    { value: 2, label: 'Disabled option with value', disabled: true },
];

const rateMessage = async (value: -1 | 0 | 1): Promise<void> => {
    ratingLoading.value = true;

    await sleep(750);

    rating.value = value;

    ratingLoading.value = false;
};

const increaseProgress = (): void => {
    progressValue.value += 1;

    if (progressValue.value >= 100) {
        if (progressIntervalId.value) {
            clearInterval(progressIntervalId.value);
        }

        progressTimeoutId.value = setTimeout(startProgressLoop, 2500);
    }
};

const startProgressLoop = async (): Promise<void> => {
    progressValue.value = 0;

    await sleep(1000);

    progressIntervalId.value = setInterval(increaseProgress, 75);
};

function handleAlertDismiss() {
    window.alert('The alert has been dismissed.');
}

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
                    <div class="flex">
                        <AppLogo />
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Typography</template>
                <div class="form">
                    <div class="form-group">
                        <div
                            v-for="type in ['text-logo', 'text-title', 'text-secondary', 'text-muted']"
                            :key="type"
                            :class="type"
                        >
                            .{{ type }}
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Toast notifications</template>
                <div class="form">
                    <div class="flex flex-wrap items-start gap-2">
                        <BaseButton
                            :leading-icon="LucideBellRing"
                            @click="notify.test()"
                        >
                            Show
                        </BaseButton>
                        <BaseButton
                            :leading-icon="LucideBellOff"
                            @click="notify.clearTest()"
                        >
                            Hide
                        </BaseButton>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Alert</template>
                <div class="form">
                    <BaseAlert
                        v-for="type in alertTypes"
                        :key="type"
                        :type="type"
                    >
                        {{ type }}
                    </BaseAlert>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <BaseAlert
                        v-for="type in alertTypes"
                        :key="type"
                        :type="type"
                        dismissible
                        @dismiss="handleAlertDismiss"
                    >
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
                            <BaseButton
                                v-for="type in buttonTypes"
                                :key="type"
                                :type="type"
                            >
                                {{ type }}
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Disabled buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton
                                v-for="type in buttonTypes"
                                :key="type"
                                :type="type"
                                disabled
                            >
                                {{ type }}
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Loading buttons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton
                                v-for="type in buttonTypes"
                                :key="type"
                                :type="type"
                                :loading="true"
                                disabled
                            >
                                {{ type }}
                            </BaseButton>
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Buttons with icons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton :leading-icon="LucideLockKeyhole">Create Link</BaseButton>
                            <BaseButton :trailing-icon="LucideSend">Send Message</BaseButton>
                        </div>
                    </div>
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="form-group">
                        <BaseLabel>Dynamic buttons with icons</BaseLabel>
                        <div class="flex flex-wrap items-start gap-2">
                            <BaseButton
                                :leading-icon="LucideThumbsUp"
                                :type="rating === 1 ? 'success' : 'secondary'"
                                :loading="ratingLoading"
                                @click="rateMessage(rating === 1 ? 0 : 1)"
                            ></BaseButton>
                            <BaseButton
                                :leading-icon="LucideThumbsDown"
                                :type="rating === -1 ? 'danger' : 'secondary'"
                                :loading="ratingLoading"
                                @click="rateMessage(rating === -1 ? 0 : -1)"
                            ></BaseButton>
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Icon Buttons</template>
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
                                :icon="LucideThumbsUp"
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
                                :icon="LucideX"
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
                        <BaseLink :to="{ name: 'ui' }">Scroll to top</BaseLink>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Badges</template>
                <div class="form">
                    <div class="flex flex-wrap items-start gap-2">
                        <BaseBadge>Default badge</BaseBadge>
                        <BaseBadge :icon="LucideLockKeyhole">Encrypted</BaseBadge>
                        <BaseBadge :icon="LucideKeyRound">Passphrase-protected</BaseBadge>
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
                            <BaseLabel for="input-default">Default input</BaseLabel>
                            <BaseInput
                                id="input-default"
                                placeholder="Placeholder"
                                v-model="input"
                            />
                        </div>

                        <div class="form-group">
                            <BaseLabel for="input-readonly">Read-only password input</BaseLabel>
                            <BaseInput
                                id="input-readonly"
                                placeholder="Placeholder"
                                v-model="input"
                                type="password"
                                readonly
                            />
                        </div>

                        <div class="form-group">
                            <BaseLabel for="input-disabled">Disabled input</BaseLabel>
                            <BaseInput
                                id="input-disabled"
                                placeholder="Placeholder"
                                v-model="input"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Textareas</template>
                <div class="form">
                    <div class="form-row">
                        <div class="form-group">
                            <BaseLabel for="textarea-default">Default textarea</BaseLabel>
                            <BaseTextarea
                                id="textarea-default"
                                placeholder="Placeholder"
                                v-model="textarea"
                            />
                        </div>

                        <div class="form-group">
                            <BaseLabel for="textarea-readonly">Read-only textarea</BaseLabel>
                            <BaseTextarea
                                id="textarea-readonly"
                                placeholder="Placeholder"
                                v-model="textarea"
                                readonly
                            />
                        </div>

                        <div class="form-group">
                            <BaseLabel for="textarea-disabled">Disabled textarea</BaseLabel>
                            <BaseTextarea
                                id="textarea-disabled"
                                placeholder="Placeholder"
                                v-model="textarea"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Selects</template>
                <div class="form">
                    <div class="form-row">
                        <div class="form-group">
                            <BaseLabel for="select-default">Optional select</BaseLabel>
                            <BaseSelect
                                id="select-default"
                                v-model="select"
                            >
                                <option
                                    v-for="option in selectOptions"
                                    :key="option.value"
                                    :value="option.value"
                                    :disabled="option.disabled"
                                >
                                    {{ option.label }}
                                </option>
                            </BaseSelect>
                        </div>
                        <div class="form-group">
                            <BaseLabel
                                for="select-required"
                                required
                            >
                                Required select
                            </BaseLabel>
                            <BaseSelect
                                id="select-required"
                                v-model="select"
                                required
                            >
                                <option
                                    v-for="option in selectOptions"
                                    :key="option.value"
                                    :value="option.value"
                                    :disabled="option.disabled"
                                >
                                    {{ option.label }}
                                </option>
                            </BaseSelect>
                        </div>
                        <div class="form-group">
                            <BaseLabel for="select-disabled">Disabled select</BaseLabel>
                            <BaseSelect
                                id="select-disabled"
                                v-model="select"
                                disabled
                            >
                                <option
                                    v-for="option in selectOptions"
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
                <template #title>Loader & spinner</template>
                <div class="form">
                    <BaseLoader padding="" />
                </div>
                <div class="form border-t-2 border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="flex justify-center">
                        <BaseSpinner />
                    </div>
                </div>
            </BaseCard>

            <BaseCard>
                <template #title>Progress bars</template>
                <div class="form">
                    <BaseProgressBar
                        v-for="progressBarType in progressBarTypes"
                        :key="progressBarType"
                        :type="progressBarType"
                        :value="progressValue"
                    />
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
                        <StatisticGridItem
                            title="Secrets created"
                            :icon="LucideLockKeyhole"
                            :value="progressValue * 1000"
                        />
                        <StatisticGridItem
                            title="Secrets revealed"
                            :icon="LucideLockKeyholeOpen"
                            icon-class="bg-emerald-500"
                            :value="progressValue * 750"
                        />
                        <StatisticGridItem
                            title="Secrets expired"
                            :icon="LucideHourglass"
                            icon-class="bg-yellow-500"
                            :value="progressValue * 150"
                        />
                        <StatisticGridItem
                            title="Secrets burned"
                            :icon="LucideFlame"
                            icon-class="bg-rose-500"
                            :value="progressValue * 100"
                        />
                    </StatisticGrid>
                </div>
            </BaseCard>
        </div>
    </div>
</template>
