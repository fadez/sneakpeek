<script setup lang="ts">
import type { NotificationType } from '@/types';
import { NOTIFICATION_TYPE_ICONS } from '@/constants';

defineProps<{
    type: NotificationType;
}>();
</script>

<template>
    <div class="Vue-Toastification__icon">
        <component
            :is="NOTIFICATION_TYPE_ICONS[type]"
            class="size-6 shrink-0"
        />
    </div>
</template>
