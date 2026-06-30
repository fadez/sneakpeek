import { ref, type Ref } from 'vue';

const now: Ref<number> = ref(Date.now());

setInterval(() => {
    now.value = Date.now();
}, 1000);

export function useNow() {
    return { now };
}
