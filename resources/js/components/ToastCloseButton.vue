<script setup>
import { computed } from 'vue';
import BaseIconButton from '@/components/BaseIconButton.vue';

const props = defineProps({
    type: {
        type: String,
        default: 'default',
        validator(value) {
            return ['default', 'success', 'info', 'warning', 'danger'].includes(value);
        },
    },
});

const iconButtonType = computed(() => (props.type === 'default' ? 'light' : props.type === 'info' ? 'primary' : props.type));
</script>

<template>
    <div class="ml-auto">
        <div class="ml-3">
            <BaseIconButton icon-classes="fa-solid fa-xmark fa-sm" size="toast" :type="iconButtonType" />
        </div>
    </div>
</template>
