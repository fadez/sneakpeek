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
        props: true,
    },
    {
        path: '/secrets/:accessToken',
        name: 'secret',
        component: Secret,
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        redirect: '/',
    },
];

export default routes;
