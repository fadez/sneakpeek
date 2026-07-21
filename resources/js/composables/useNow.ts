import type { Ref } from 'vue';
import { ref } from 'vue';

const now: Ref<number> = ref(Date.now());

setInterval(() => {
    now.value = Date.now();
}, 1000);

export function useNow() {
    return { now };
}
