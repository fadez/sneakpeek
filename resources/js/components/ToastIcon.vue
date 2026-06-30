<script setup lang="ts">
import type { NotificationType } from '@/types';
import { NOTIFICATION_TYPE_ICONS } from '@/constants';

defineProps<{
    type: NotificationType;
}>();
</script>

<template>
    <div class="my-auto mr-1">
        <i :class="NOTIFICATION_TYPE_ICONS[type]"></i>
    </div>
</template>
