<script setup lang="ts">
import type { NotificationType } from '@/types';
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';
import { NOTIFICATION_TYPE_ICONS } from '@/constants';
import BaseIconButton from '@/components/BaseIconButton.vue';

const {
    type = 'info',
    showIcon = true,
    icon = '',
    dismissible = false,
} = defineProps<{
    type?: NotificationType;
    showIcon?: boolean;
    icon?: string;
    dismissible?: boolean;
}>();

const emit = defineEmits<{
    dismiss: [];
}>();

const route = useRoute();

const dismissed = ref(false);

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

const iconClasses = computed(() => icon || NOTIFICATION_TYPE_ICONS[type]);

const dismiss = (): void => {
    if (route.name !== 'ui') {
        dismissed.value = true;
    }

    emit('dismiss');
};
</script>

<template>
    <div
        v-if="!dismissed"
        class="flex items-center gap-2 rounded-md border-2 px-4 py-3"
        :class="alertClasses"
    >
        <i
            v-if="showIcon"
            :class="iconClasses"
        ></i>

        <div>
            <slot />
        </div>

        <div
            v-if="dismissible"
            class="ml-auto flex"
        >
            <BaseIconButton
                :type="type === 'neutral' ? 'light' : type"
                icon="fa-solid fa-xmark"
                size="sm"
                :colored="true"
                @click="dismiss"
            />
        </div>
    </div>
</template>
