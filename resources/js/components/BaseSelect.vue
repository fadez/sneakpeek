<script setup>
import { useAttrs } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const model = defineModel({
    type: [String, Number],
});

const attrs = useAttrs();

const handleChange = (event) => {
    model.value = event.target.value;
    event.target.blur();
};
</script>

<template>
    <div class="relative">
        <select
            v-bind="attrs"
            class="w-full appearance-none rounded-md border-2 border-zinc-200 bg-white px-3 py-3.5 transition-all select-none focus:border-sky-500 focus:outline-hidden dark:border-zinc-700 dark:bg-black"
            :value="model"
            @change="handleChange"
        >
            <slot></slot>
        </select>
        <i class="fa-solid fa-chevron-down pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-muted"></i>
    </div>
</template>
