import { ref, computed, watch, onUnmounted } from 'vue';

export function useSecretExpirationProgress(secret, onFinish) {
    const now = ref(Date.now());
    let interval = null;

    const start = () => {
        if (interval) clearInterval(interval);

        interval = setInterval(() => {
            now.value = Date.now();
        }, 100);
    };

    const stop = () => {
        clearInterval(interval);
        interval = null;
    };

    const createdAt = computed(() => (secret.value ? Date.parse(secret.value.created_at) : 0));
    const expiresAt = computed(() => (secret.value ? Date.parse(secret.value.expires_at) : 0));
    const duration = computed(() => expiresAt.value - createdAt.value);

    const progress = computed(() => {
        if (!secret.value || secret.value.is_expired || duration.value <= 0) {
            return 100;
        }

        const elapsed = now.value - createdAt.value;
        const percentage = (elapsed / duration.value) * 100;

        return Math.max(0, Math.min(percentage, 100));
    });

    let finished = false;

    const handleSecretChange = (value) => {
        if (!value) return;

        finished = false;

        start();
    };

    const handleProgressChange = (value) => {
        if (!finished && value >= 100) {
            finished = true;
            stop();

            if (typeof onFinish === 'function') {
                onFinish();
            }
        }
    };

    watch(secret, handleSecretChange, { immediate: true });
    watch(progress, handleProgressChange);

    onUnmounted(stop);

    return progress;
}
