<script setup>
import BaseIconButton from '@/components/BaseIconButton.vue';

const props = defineProps({
    type: {
        type: String,
        default: 'default',
        validator(value) {
            // Value must be a toast type
            return ['default', 'success', 'info', 'warning', 'danger'].includes(value);
        },
    },
});
</script>

<template>
    <div class="ml-auto">
        <div class="ml-3">
            <BaseIconButton :type="type === 'default' ? 'light' : type" icon="fa-solid fa-xmark" size="sm" :colored="true" />
        </div>
    </div>
</template>
