import { defineStore } from 'pinia';
import { useToast } from 'vue-toastification';

const toast = useToast();

export const useNotificationStore = defineStore('notifications', {
    state: () => ({
        internetDisconnectedToastId: null,
        neutralTestToastId: null,
        successTestToastId: null,
        dangerTestToastId: null,
        infoTestToastId: null,
        warningTestToastId: null,
    }),

    actions: {
        // Proxy methods to vue-toastification
        neutral(content, options) {
            return toast(content, options);
        },
        success(content, options) {
            return toast.success(content, options);
        },
        danger(content, options) {
            return toast.error(content, options);
        },
        info(content, options) {
            return toast.info(content, options);
        },
        warning(content, options) {
            return toast.warning(content, options);
        },
        dismiss(toastId, toastIdVarName) {
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

            // Gather toast IDs and their corresponding state variable names
            const testToasts = [
                { id: this.neutralTestToastId, varName: 'neutralTestToastId' },
                { id: this.successTestToastId, varName: 'successTestToastId' },
                { id: this.dangerTestToastId, varName: 'dangerTestToastId' },
                { id: this.infoTestToastId, varName: 'infoTestToastId' },
                { id: this.warningTestToastId, varName: 'warningTestToastId' },
            ].filter((toast) => toast.id != null);

            // Dismiss toasts one by one to have pretty animations vs just calling "this.clear()"
            testToasts.forEach((toast, i) => {
                setTimeout(() => this.dismiss(toast.id, toast.varName), delay * i);
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

            this.dismiss(this.internetDisconnectedToastId, 'internetDisconnectedToastId');
        },
    },
});
