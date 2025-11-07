import {createRouter, createWebHistory} from 'vue-router';


const routes = [
    {
        path: '/',
        redirect: '/dashboard'
    },
    {
        path: '/show-image',
        component: () => import('../pages/ShowImage.vue')
    },
    {
        path: '/dashboard',
        redirect: '/dashboard/register',
        component: () => import('../pages/Dashboard.vue'),
        children: [
            {
                path: '/dashboard/register',
                component: () => import('../pages/RegisterImage.vue')
            },
            {
                path: '/dashboard/unregister',
                component: () => import('../pages/UnRegisterImage.vue')
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


export default router;
