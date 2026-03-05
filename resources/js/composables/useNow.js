import { ref } from 'vue';

const now = ref(Date.now());

setInterval(() => {
    now.value = Date.now();
}, 1000);

export function useNow() {
    return { now };
}
