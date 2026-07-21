import type { ComputedRef, Ref } from 'vue';
import { ref, computed, watch, onUnmounted } from 'vue';

interface Secret {
    created_at: string;
    expires_at: string;
    is_expired: boolean;
}

export function useSecretExpirationProgress(secret: Ref<Secret | null>, onFinish?: () => void): ComputedRef<number> {
    const now = ref(Date.now());
    let interval: ReturnType<typeof setInterval> | null = null;

    const start = (): void => {
        if (interval) clearInterval(interval);

        interval = setInterval(() => {
            now.value = Date.now();
        }, 100);
    };

    const stop = (): void => {
        if (interval) {
            clearInterval(interval);
            interval = null;
        }
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

    const handleSecretChange = (value: Secret | null): void => {
        if (!value) return;

        finished = false;
        start();
    };

    const handleProgressChange = (value: number): void => {
        if (!finished && value >= 100) {
            finished = true;
            stop();

            onFinish?.();
        }
    };

    watch(secret, handleSecretChange, { immediate: true });
    watch(progress, handleProgressChange);

    onUnmounted(stop);

    return progress;
}
