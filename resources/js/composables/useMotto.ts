import { ref } from 'vue';

const defaultMotto = 'Secure, one-time secret sharing made simple.';

const satiricalMottos = [
    'Secure, one-time secret sharing. Zero snitching. Allegedly.',
    'We, and a handful of intelligence agencies, keep your secrets safe.',
    'We never snitch. Except when we do.',
];

const motto = ref(defaultMotto);

// Show a random satirical motto to an unsuspecting visitor with a 5% chance
function pickMotto() {
    return Math.random() < 0.05 ? satiricalMottos[Math.floor(Math.random() * satiricalMottos.length)] : defaultMotto;
}

export function rerollMotto() {
    motto.value = pickMotto();
}

export function useMotto() {
    return { motto };
}
