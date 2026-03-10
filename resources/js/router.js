import { createRouter, createWebHistory } from 'vue-router';
import { useNotificationStore } from '@/stores/notifications';
import Dashboard from '@/pages/Dashboard.vue';
import Home from '@/pages/Home.vue';
import Receipt from '@/pages/Receipt.vue';
import Secret from '@/pages/Secret.vue';
import UI from '@/pages/UI.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/receipts/:id',
        name: 'receipt',
        component: Receipt,
    },
    {
        path: '/secrets/:id',
        name: 'secret',
        component: Secret,
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/ui',
        name: 'ui',
        component: UI,
    },
    {
        path: '/:pathMatch(.*)*',
        name: '404',
        beforeEnter: (to, from, next) => {
            const notify = useNotificationStore();

            notify.pageNotFound();

            next({ name: 'home', replace: true });
        },
    },
];

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

export default router;
