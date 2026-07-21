<script setup lang="ts">
import type { InputTypeHTMLAttribute, Ref } from 'vue';
import { computed, ref, useAttrs, useTemplateRef } from 'vue';
import { LucideEye, LucideEyeOff } from '@lucide/vue';

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string | number>();

const attrs = useAttrs();

const input = useTemplateRef('input') as Ref<HTMLInputElement | null>;

const showPassword = ref(false);

const inputType = computed(() => attrs.type as InputTypeHTMLAttribute | undefined);

const inputClasses = computed(() => ({
    'group-hover:not-focus:border-zinc-325 dark:group-hover:not-focus:border-zinc-500': !('disabled' in attrs),
    'pr-10': inputType.value === 'password',
}));

const showPasswordButtonClasses = computed(() => ('disabled' in attrs ? '' : 'hover:text-disabled'));

defineExpose({
    focus: () => input.value?.focus(),
    select: () => input.value?.select(),
});
</script>

<template>
    <div class="group relative grow">
        <input
            ref="input"
            v-bind="attrs"
            class="w-full rounded-md border-2 border-zinc-200 bg-white p-3 transition-all focus:border-sky-500 focus:outline-hidden dark:border-zinc-700 dark:bg-black"
            :class="inputClasses"
            :type="inputType === 'password' ? (showPassword ? 'text' : 'password') : inputType"
            v-model="model"
        />

        <button
            v-if="inputType === 'password' && !('disabled' in attrs)"
            type="button"
            tabindex="-1"
            class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-muted transition-all"
            :class="showPasswordButtonClasses"
            @click="showPassword = !showPassword"
        >
            <component
                :is="showPassword ? LucideEyeOff : LucideEye"
                class="size-5"
            />
        </button>
    </div>
</template>
