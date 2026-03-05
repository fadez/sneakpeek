<script setup>
import { computed } from 'vue';
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

// Convert toast type to appropriate button type
const iconButtonType = computed(() => {
    if (props.type === 'default') return 'light';
    if (props.type === 'info') return 'primary';

    return props.type;
});
</script>

<template>
    <div class="ml-auto">
        <div class="ml-3">
            <BaseIconButton :type="iconButtonType" icon="fa-solid fa-xmark" size="toast" />
        </div>
    </div>
</template>
