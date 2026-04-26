<script setup>
import { NOTIFICATION_TYPE_ICONS } from '@/constants/icons';

const { type } = defineProps({
    type: {
        type: String,
        required: true,
        validator: (value) => ['neutral', 'success', 'info', 'warning', 'danger'].includes(value),
    },
});
</script>

<template>
    <div class="my-auto mr-1">
        <i :class="NOTIFICATION_TYPE_ICONS[type]"></i>
    </div>
</template>
