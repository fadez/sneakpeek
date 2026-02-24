import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from '@/router';
import toastNotifications from '@/toasts';
import App from '@/App.vue';

// Import all files from the resources directories to ensure they're available to Vite and the app
import.meta.glob(['../images/**']);

// Only bootstrap Vue app if #app element exists
const appElement = document.getElementById('app');

if (appElement) {
    const pinia = createPinia();
    const app = createApp(App);

    app.use(pinia);
    app.use(toastNotifications);
    app.use(router);

    app.mount(appElement);
}
