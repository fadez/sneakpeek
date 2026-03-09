<script setup>
import { useTemplateRef } from 'vue';

const model = defineModel({
    type: [String, Number],
});

defineExpose({
    focus: () => input.value?.focus(),
    select: () => input.value?.select(),
});

const input = useTemplateRef('input');
</script>

<template>
    <input
        ref="input"
        class="w-full rounded-md border-2 border-zinc-200 bg-white p-3 transition-all focus:border-sky-500 focus:outline-hidden dark:border-zinc-700 dark:bg-black"
        :value="model"
        @input="model = $event.target.value"
    />
</template>
