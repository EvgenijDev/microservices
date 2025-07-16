import { createRouter, createWebHistory } from 'vue-router';
import ProductList from '../views/ProductList.vue';
import ProductForm from '../views/ProductForm.vue';

const routes = [
    {
        path: '/',
        redirect: '/products'
    },
    {
        path: '/products',
        name: 'products',
        component: ProductList
    },
    {
        path: '/products/create',
        name: 'product.create',
        component: ProductForm
    },
    {
        path: '/products/:id/edit',
        name: 'product.edit',
        component: ProductForm,
        props: true
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;