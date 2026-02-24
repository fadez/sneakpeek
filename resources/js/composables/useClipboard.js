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

            options.onSuccess ? options.onSuccess() : notify.copiedToClipboard();
        } catch (error) {
            options.onError ? options.onError() : notify.failedToCopyToClipboard();
        }
    };

    return { copyToClipboard };
}
