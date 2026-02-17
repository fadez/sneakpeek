import { h } from 'vue';
import Toast, { POSITION as TOAST_POSITION, TYPE as TOAST_TYPE } from 'vue-toastification';
import ToastCloseButton from '@/components/ToastCloseButton.vue';

const toastNotifications = {
    install(app) {
        app.use(Toast, {
            transition: 'Vue-Toastification__bounce',
            maxToasts: 10,
            timeout: 4000,
            position: TOAST_POSITION.BOTTOM_RIGHT,
            newestOnTop: true,
            icon: false,
            draggable: false,
            closeOnClick: false,
            hideProgressBar: true,
            pauseOnHover: false,
            pauseOnFocusLoss: false,
            shareAppContext: true,
            toastDefaults: {
                [TOAST_TYPE.DEFAULT]: {
                    closeButton: () => h(ToastCloseButton, { type: 'default' }),
                },
                [TOAST_TYPE.SUCCESS]: {
                    closeButton: () => h(ToastCloseButton, { type: 'success' }),
                },
                [TOAST_TYPE.INFO]: {
                    closeButton: () => h(ToastCloseButton, { type: 'info' }),
                    timeout: 2500,
                },
                [TOAST_TYPE.WARNING]: {
                    closeButton: () => h(ToastCloseButton, { type: 'warning' }),
                },
                [TOAST_TYPE.ERROR]: {
                    closeButton: () => h(ToastCloseButton, { type: 'danger' }),
                    timeout: 7500,
                    pauseOnHover: true,
                },
            },
            filterBeforeCreate: (toast, toasts) => {
                if (toasts.filter((t) => t.content === toast.content).length !== 0) {
                    return false; // Returning false discards duplicate toast
                }

                return toast;
            },
        });
    },
};

export default toastNotifications;
