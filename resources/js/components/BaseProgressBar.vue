<script setup>
import { ref, watch, computed } from 'vue';

const {
    value = 0.0,
    label = '',
    valueLabel = '',
    type = 'default',
} = defineProps({
    value: Number,
    label: String,
    valueLabel: String,
    type: {
        type: String,
        validator: (value) => ['default', 'success', 'danger', 'info', 'warning', 'expiration'].includes(value),
    },
});

const prevValue = ref(value);
const isIncreasing = ref(true);

const typeClasses = {
    default: 'bg-zinc-400',
    success: 'bg-emerald-500',
    danger: 'bg-red-500',
    info: 'bg-sky-500',
    warning: 'bg-yellow-500',
    expiration: 'bg-gradient-to-r from-emerald-500 via-yellow-500 to-red-500',
};

const barClasses = computed(() => typeClasses[type]);

watch(
    () => value,
    (newVal, oldVal) => {
        isIncreasing.value = newVal >= oldVal;
        prevValue.value = oldVal;
    },
);
</script>

<template>
    <div
        class="form-group"
        :class="[label ? 'py-1' : '']"
    >
        <div
            v-if="label"
            class="flex justify-between text-sm text-muted"
        >
            <span>{{ label }}</span>
            <span
                v-if="valueLabel"
                class="text-right"
            >
                {{ valueLabel }}
            </span>
        </div>
        <div class="flex h-3 w-full overflow-hidden rounded-md bg-zinc-300 dark:bg-zinc-700">
            <div
                class="rounded-ful flex flex-col justify-center overflow-hidden whitespace-nowrap"
                :class="[barClasses, isIncreasing ? 'transition-[width] duration-200 ease-linear' : '']"
                :style="{ width: `${value}%` }"
            ></div>
        </div>
    </div>
</template>
