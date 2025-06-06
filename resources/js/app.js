import { createApp } from 'vue';
import App from '@/App.vue';
import { createWebHistory, createRouter } from 'vue-router';
import routes from '@/routes';

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
app.mount('#app');
