import { useToast } from 'vue-toastification';

export function useClipboard() {
    const toast = useToast();

    /**
     * Copy a string to the clipboard and show toast notification.
     * @param {string} text
     * @param {string} [successMessage='Copied!']
     * @param {string} [errorMessage]
     */
    const copyToClipboard = async (text, successMessage = 'Copied!', errorMessage) => {
        try {
            await navigator.clipboard.writeText(text);
            toast.info(successMessage);
        } catch (error) {
            // Do not show error toast if errorMessage is not set at all
            if (errorMessage === undefined) return;

            toast.error(typeof errorMessage === 'string' ? errorMessage : 'Failed to copy.');
        }
    };

    return { copyToClipboard };
}
