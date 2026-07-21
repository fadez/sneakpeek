<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useIntervalFn } from '@vueuse/core';

const {
    link,
    text = 'SneakPeek',
    animationDuration = 750,
    animateOnMount = true,
} = defineProps<{
    link?: boolean;
    text?: string;
    animationDuration?: number;
    animateOnMount?: boolean;
}>();

// The set of characters used for the animated scrambling effect.
const SCRAMBLE_CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

const displayText = ref(text.split(''));
const iterations = ref(0);

function startAnimation() {
    iterations.value = 0;
    resume();
}

// Calculate duration of each animation step so the whole animation matches the provided duration
const animationStepDuration = computed(() => animationDuration / (text.length * 10));

const { pause, resume } = useIntervalFn(
    () => {
        if (iterations.value < text.length) {
            displayText.value = text.split('').map((letter, i) => {
                if (letter === ' ') return ' ';

                if (i <= iterations.value) return text[i] ?? '';

                return SCRAMBLE_CHARACTERS[Math.floor(Math.random() * SCRAMBLE_CHARACTERS.length)];
            });

            iterations.value += 0.1;
        } else {
            pause();
        }
    },
    animationStepDuration,
    { immediate: false },
);

onMounted(() => {
    if (animateOnMount) {
        startAnimation();
    }
});
</script>

<template>
    <component
        :is="link ? 'RouterLink' : 'div'"
        v-bind="link ? { to: '/' } : {}"
        class="flex cursor-pointer items-center rounded-md select-none focus-visible:ring-2 focus-visible:outline-hidden dark:focus-visible:ring-white"
        tabindex="0"
        @keyup.enter="startAnimation"
        @click="startAnimation"
    >
        <img
            src="/public/logo.svg"
            class="pointer-events-none size-12 select-none"
            alt="SneakPeek logo"
        />
        <div class="pointer-events-none flex overflow-hidden p-1 text-logo select-none">
            <span
                v-for="(letter, i) in displayText"
                :key="i"
                class="min-w-[0.1em]"
            >
                {{ letter }}
            </span>
        </div>
    </component>
</template>
