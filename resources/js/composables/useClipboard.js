import { useNotificationStore } from '@/stores/notifications';

export function useClipboard() {
    const notify = useNotificationStore();

    /**
     * Copy a string to the clipboard and show notification.
     *
     * @param {string} text
     * @param {Object} [options]
     * @param {Function} [options.onSuccess]
     * @param {Function} [options.onError]
     */
    const copyToClipboard = async (text, options = {}) => {
        try {
            await navigator.clipboard.writeText(text);

            if (options.onSuccess) {
                options.onSuccess();
            } else {
                notify.copiedToClipboard();
            }
        } catch {
            if (options.onError) {
                options.onError();
            } else {
                notify.failedToCopyToClipboard();
            }
        }
    };

    return { copyToClipboard };
}
