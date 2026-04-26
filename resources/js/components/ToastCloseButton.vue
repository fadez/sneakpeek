<script setup>
import BaseIconButton from '@/components/BaseIconButton.vue';

const { type } = defineProps({
    type: {
        type: String,
        required: true,
        validator: (value) => ['neutral', 'success', 'info', 'warning', 'danger'].includes(value),
    },
});
</script>

<template>
    <div class="ml-auto">
        <div class="ml-2">
            <BaseIconButton
                :type="type === 'neutral' ? 'light' : type"
                icon="fa-solid fa-xmark"
                size="sm"
                :colored="true"
            />
        </div>
    </div>
</template>
