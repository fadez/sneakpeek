import { useToast } from 'vue-toastification';
import Home from '@/pages/Home.vue';
import Receipt from '@/pages/Receipt.vue';
import Secret from '@/pages/Secret.vue';

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
        path: '/:pathMatch(.*)*',
        name: '404',
        beforeEnter: (to, from, next) => {
            const toast = useToast();

            toast.error("Whoops! We couldn't find that page.");

            next({ name: 'home', replace: true });
        },
    },
];

export default routes;
