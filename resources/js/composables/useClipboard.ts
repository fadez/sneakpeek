import { useNotificationStore } from '@/stores/notifications';

interface CopyToClipboardOptions {
    onSuccess?: () => void;
    onError?: () => void;
}

export function useClipboard() {
    const notify = useNotificationStore();

    const copyToClipboard = async (text: string, options: CopyToClipboardOptions = {}): Promise<void> => {
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
