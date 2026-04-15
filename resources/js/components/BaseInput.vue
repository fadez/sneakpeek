<script setup>
import { computed, ref, useAttrs, useTemplateRef } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const model = defineModel({
    type: [String, Number],
});

defineExpose({
    focus: () => input.value?.focus(),
    select: () => input.value?.select(),
});

const attrs = useAttrs();

const input = useTemplateRef('input');
const showPassword = ref(false);

const inputClasses = computed(() => ({
    'group-hover:not-focus:border-zinc-325 dark:group-hover:not-focus:border-zinc-500': !('disabled' in attrs),
    'pr-10': attrs.type === 'password',
}));

const showPasswordButtonClasses = computed(() => ('disabled' in attrs ? '' : 'hover:text-secondary'));
</script>

<template>
    <div class="group relative grow">
        <input
            ref="input"
            v-bind="attrs"
            class="w-full rounded-md border-2 border-zinc-200 bg-white p-3 transition-all focus:border-sky-500 focus:outline-hidden dark:border-zinc-700 dark:bg-black"
            :class="inputClasses"
            :type="attrs.type === 'password' ? (showPassword ? 'text' : 'password') : attrs.type"
            v-model="model"
        />
        <button
            v-if="attrs.type === 'password' && !('disabled' in attrs)"
            type="button"
            tabindex="-1"
            class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-muted transition-all"
            :class="showPasswordButtonClasses"
            @click="showPassword = !showPassword"
        >
            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
        </button>
    </div>
</template>
