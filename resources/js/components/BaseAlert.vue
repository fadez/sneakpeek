<script setup>
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';
import { NOTIFICATION_TYPE_ICONS } from '@/constants/icons';
import BaseIconButton from '@/components/BaseIconButton.vue';

const props = defineProps({
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['neutral', 'success', 'danger', 'info', 'warning'].includes(value),
    },
    showIcon: {
        type: Boolean,
        default: true,
    },
    icon: {
        type: String,
        default: '',
    },
    dismissible: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['dismiss']);

const dismissed = ref(false);
const route = useRoute();

// prettier-ignore
const typeClasses = {
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

const alertClasses = computed(() => {
    return typeClasses[props.type];
});

const iconClasses = computed(() => (!!props.icon ? props.icon : NOTIFICATION_TYPE_ICONS[props.type]));

const dismiss = () => {
    if (route.name !== 'ui') {
        dismissed.value = true;
    }

    emit('dismiss');
};
</script>

<template>
    <div v-if="!dismissed" class="flex items-center gap-2 rounded-md border-2 px-4 py-3" :class="alertClasses">
        <i v-if="showIcon" :class="iconClasses"></i>
        <div>
            <slot></slot>
        </div>
        <div v-if="dismissible" class="ml-auto flex">
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
