import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import routes from '@/routes';
import toastNotifications from '@/toasts';
import App from '@/App.vue';

const app = createApp(App);

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (from.name === to.name) {
            // If navigating to the same page, scroll smoothly to the top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // Always scroll to top by default
            return { top: 0 };
        }
    },
});

app.use(router);

app.use(toastNotifications);

app.mount('#app');
