import type { ToastID, ToastContent, ToastOptions } from 'vue-toastification/dist/types/types';
import { defineStore } from 'pinia';
import { useToast } from 'vue-toastification';

type ToastIdKey =
    | 'internetDisconnectedToastId'
    | 'neutralTestToastId'
    | 'successTestToastId'
    | 'dangerTestToastId'
    | 'infoTestToastId'
    | 'warningTestToastId';

type TestToastItem = {
    id: ToastID | null;
    varName: ToastIdKey;
};

const toast = useToast();

export const useNotificationStore = defineStore('notifications', {
    state: () => ({
        internetDisconnectedToastId: null as ToastID | null,
        neutralTestToastId: null as ToastID | null,
        successTestToastId: null as ToastID | null,
        dangerTestToastId: null as ToastID | null,
        infoTestToastId: null as ToastID | null,
        warningTestToastId: null as ToastID | null,
    }),

    actions: {
        // Proxy methods to vue-toastification
        neutral(content: ToastContent, options?: ToastOptions) {
            return toast(content, options);
        },
        success(content: ToastContent, options?: Omit<ToastOptions, 'type'>) {
            return toast.success(content, options);
        },
        danger(content: ToastContent, options?: Omit<ToastOptions, 'type'>) {
            return toast.error(content, options);
        },
        info(content: ToastContent, options?: Omit<ToastOptions, 'type'>) {
            return toast.info(content, options);
        },
        warning(content: ToastContent, options?: Omit<ToastOptions, 'type'>) {
            return toast.warning(content, options);
        },
        dismiss(toastId: ToastID, toastIdVarName: ToastIdKey) {
            if (toastId !== undefined && toastId !== null) {
                toast.dismiss(toastId);
            }

            if (toastIdVarName) {
                this[toastIdVarName] = null;
            }
        },
        clear() {
            toast.clear();
        },

        // Methods to preview and test all notifications in UI
        test() {
            this.neutralTestToastId = this.neutral('neutral notification', { timeout: false });
            this.successTestToastId = this.success('success notification', { timeout: false });
            this.dangerTestToastId = this.danger('danger notification', { timeout: false });
            this.infoTestToastId = this.info('info notification', { timeout: false });
            this.warningTestToastId = this.warning('warning notification', { timeout: false });
        },
        clearTest() {
            let delay = 200; // ms

            // Collect all test toast IDs along with their related store variable names
            const testToasts: TestToastItem[] = [
                { id: this.neutralTestToastId, varName: 'neutralTestToastId' },
                { id: this.successTestToastId, varName: 'successTestToastId' },
                { id: this.dangerTestToastId, varName: 'dangerTestToastId' },
                { id: this.infoTestToastId, varName: 'infoTestToastId' },
                { id: this.warningTestToastId, varName: 'warningTestToastId' },
            ].filter((toast): toast is TestToastItem & { id: ToastID } => toast.id != null);

            // Dismiss toasts one by one to have pretty animations vs just calling "this.clear()"
            testToasts.forEach((toast, i) => {
                setTimeout(() => {
                    if (toast.id != null) this.dismiss(toast.id, toast.varName);
                }, delay * i);
            });
        },

        // All notification messages in the app
        secretCreated() {
            this.success('Secret has been created.');
        },
        secretRevealed() {
            this.success('Secret has been revealed.');
        },
        secretBurned() {
            this.success('Secret has been burned.');
        },
        secretLinkCopied() {
            this.info("Secret link copied! You're ready to share.");
        },
        failedToCopySecretLink() {
            this.danger('Failed to copy secret link.');
        },
        secretMessageCopied() {
            this.info('Secret message copied!');
        },
        failedToCopySecretMessage() {
            this.danger('Failed to copy secret message.');
        },
        copiedToClipboard() {
            this.info('Copied to clipboard!');
        },
        failedToCopyToClipboard() {
            this.danger('Failed to copy to clipboard.');
        },
        pageNotFound() {
            this.danger("Whoops! We couldn't find that page.");
        },
        internetDisconnected() {
            this.internetDisconnectedToastId = this.danger("You're offline, trying to reconnect...", {
                timeout: false,
                closeButton: false,
            });
        },
        internetReconnected() {
            this.success('You are back online!', { closeButton: false });

            if (this.internetDisconnectedToastId) this.dismiss(this.internetDisconnectedToastId, 'internetDisconnectedToastId');
        },
    },
});
