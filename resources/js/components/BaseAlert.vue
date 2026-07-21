<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';
import type { NotificationType } from '@/types';
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';
import { LucideX } from '@lucide/vue';
import { NOTIFICATION_TYPE_ICONS } from '@/constants';
import BaseIconButton from '@/components/BaseIconButton.vue';

const {
    type = 'info',
    icon,
    showIcon = true,
    dismissible = false,
} = defineProps<{
    type?: NotificationType;
    showIcon?: boolean;
    icon?: LucideIcon;
    dismissible?: boolean;
}>();

const emit = defineEmits<{
    dismiss: [];
}>();

// prettier-ignore
const typeClasses: Record<NotificationType, string> = {
    neutral: [
        'bg-white',
        'text-zinc-950',
        'border-zinc-950',
        'dark:bg-zinc-900',
        'dark:text-zinc-100',
        'dark:border-zinc-100',
    ].join(' '),

    success: [
        'bg-emerald-100',
        'text-emerald-500',
        'border-emerald-500',
        'dark:bg-emerald-950',
        'dark:border-emerald-600',
    ].join(' '),

    danger: [
        'bg-rose-100',
        'text-rose-500',
        'border-rose-500',
        'dark:bg-rose-950',
        'dark:border-rose-600',
    ].join(' '),

    info: [
        'bg-sky-100',
        'text-sky-500',
        'border-sky-500',
        'dark:bg-sky-950',
        'dark:border-sky-600',
    ].join(' '),

    warning: [
        'bg-yellow-100',
        'text-yellow-500',
        'border-yellow-500',
        'dark:bg-yellow-950',
        'dark:border-yellow-600',
    ].join(' '),
};

const alertClasses = computed(() => typeClasses[type]);

const iconComponent = computed(() => icon || NOTIFICATION_TYPE_ICONS[type]);

const dismiss = (): void => {
    emit('dismiss');
};
</script>

<template>
    <div
        class="flex items-center gap-2 rounded-md border-2 px-4 py-3"
        :class="alertClasses"
    >
        <component
            v-if="showIcon"
            :is="iconComponent"
        />

        <div>
            <slot />
        </div>

        <div
            v-if="dismissible"
            class="ml-auto flex"
        >
            <BaseIconButton
                :type="type === 'neutral' ? 'light' : type"
                :icon="LucideX"
                :colored="true"
                size="sm"
                @click="dismiss"
            />
        </div>
    </div>
</template>
