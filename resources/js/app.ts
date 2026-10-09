import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { configureEcho, echo } from '@laravel/echo-vue';
import router from '@/router';
import { onRequestError, onResolveSocketId } from '@/http';
import toastNotifications from '@/toasts';
import { useNotificationStore } from '@/stores/notifications';
import App from '@/App.vue';

// Import all files from the resources directories to ensure they're available to Vite and the app
import.meta.glob(['../images/**'], { eager: true });

// Only bootstrap Vue app if "#app" element exists
if (document.querySelector('#app')) {
    // Configure Laravel Echo
    configureEcho({ broadcaster: import.meta.env.VITE_BROADCAST_CONNECTION });

    // Send the Echo socket ID as an "X-Socket-ID" header with every HTTP request
    onResolveSocketId(() => echo().socketId());

    const pinia = createPinia();
    const app = createApp(App);

    app.use(pinia);
    app.use(toastNotifications);
    app.use(router);

    // Show a toast for every failed HTTP request
    onRequestError((error) => {
        useNotificationStore().danger(error.message ?? 'An unexpected error has occurred.');
    });

    app.mount('#app');
}
