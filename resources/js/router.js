import { createRouter, createWebHistory } from 'vue-router';
import { useNotificationStore } from '@/stores/notifications';

// Eager-loaded: users arrive at these pages directly via external links
import Home from '@/pages/Home.vue';
import Receipt from '@/pages/Receipt.vue';
import Secret from '@/pages/Secret.vue';

// Lazy-loaded: non-essential routes, aggressively prefetched by Vite
// after the initial load so there's no perceived delay on navigation
const Dashboard = () => import('@/pages/Dashboard.vue');
const UI = () => import('@/pages/UI.vue');

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
            // When navigating to the same route, scroll smoothly to the top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // When navigating to a different route, scroll instantly to the top
            return { top: 0 };
        }
    },
});

export default router;
