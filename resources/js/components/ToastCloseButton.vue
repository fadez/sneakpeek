<script setup lang="ts">
import type { NotificationType } from '@/types';
import BaseIconButton from '@/components/BaseIconButton.vue';

defineProps<{
    type: NotificationType;
}>();
</script>

<template>
    <div class="ml-auto">
        <div class="ml-2">
            <BaseIconButton
                :type="type === 'neutral' ? 'light' : type"
                icon="fa-solid fa-xmark"
                :colored="true"
                size="sm"
            />
        </div>
    </div>
</template>
