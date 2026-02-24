import { defineStore } from 'pinia';
import { useToast } from 'vue-toastification';

const toast = useToast();

export const useNotificationStore = defineStore('notifications', {
    state: () => ({
        internetDisconnectedToastId: null,
    }),

    actions: {
        // Proxy methods to VueToastification
        default(content, options) {
            return toast(content, options);
        },
        success(content, options) {
            return toast.success(content, options);
        },
        info(content, options) {
            return toast.info(content, options);
        },
        warning(content, options) {
            return toast.warning(content, options);
        },
        error(content, options) {
            return toast.error(content, options);
        },

        // Custom notification messages
        secretCreated() {
            toast.success('Secret has been created.');
        },
        secretRevealed() {
            toast.success('Secret has been revealed.');
        },
        secretDeleted() {
            toast.success('Secret has been deleted.');
        },
        secretLinkCopied() {
            toast.info("Secret link copied! You're ready to share.");
        },
        failedToCopySecretLink() {
            toast.error('Failed to copy secret link.');
        },
        secretMessageCopied() {
            toast.info('Secret message copied!');
        },
        failedToCopySecretMessage() {
            toast.error('Failed to copy secret message.');
        },
        copiedToClipboard() {
            toast.info('Copied to clipboard!');
        },
        failedToCopyToClipboard() {
            toast.error('Failed to copy to clipboard.');
        },
        pageNotFound() {
            toast.error("Whoops! We couldn't find that page.");
        },
        internetDisconnected() {
            this.internetDisconnectedToastId = toast.error("You're offline, trying to reconnect...", { timeout: false, closeButton: false });
        },
        internetReconnected() {
            toast.success('You are back online!', { closeButton: false });

            toast.dismiss(this.internetDisconnectedToastId);

            this.internetDisconnectedToastId = null;
        },
    },
});
