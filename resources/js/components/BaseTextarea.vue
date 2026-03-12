<script setup>
import { computed, useAttrs, useTemplateRef } from 'vue';

const model = defineModel({
    type: String,
});

defineExpose({
    focus: () => textarea.value?.focus(),
    select: () => textarea.value?.select(),
});

const attrs = useAttrs();

const textarea = useTemplateRef('textarea');

const textareaClasses = computed(() => ('disabled' in attrs ? '' : 'hover:not-focus:border-zinc-325 dark:hover:not-focus:border-zinc-500'));
</script>

<template>
    <textarea
        ref="textarea"
        class="w-full resize-none rounded-md border-2 border-zinc-200 bg-white p-3 transition-all focus:border-sky-500 focus:outline-hidden dark:border-zinc-700 dark:bg-black"
        :class="textareaClasses"
        v-model="model"
    ></textarea>
</template>
