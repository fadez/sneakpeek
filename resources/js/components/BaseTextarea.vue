<script setup>
import { useTemplateRef } from 'vue';

const model = defineModel({
    type: String,
});

defineExpose({
    focus: () => textarea.value?.focus(),
    select: () => textarea.value?.select(),
});

const textarea = useTemplateRef('textarea');
</script>

<template>
    <textarea
        ref="textarea"
        class="w-full resize-none rounded-md border-2 border-zinc-200 bg-white p-3 transition-all focus:border-sky-500 focus:outline-hidden dark:border-zinc-700 dark:bg-black"
        :value="model"
        @input="model = $event.target.value"
    ></textarea>
</template>
