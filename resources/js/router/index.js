// import { createRouter, createWebHistory } from 'vue-router'
import { createRouter, createWebHashHistory } from 'vue-router'
import ProductList from '../views/ProductList.vue'
import ProductForm from '../views/ProductForm.vue'
import ProductAuth from '../components/ProductAuth.vue'
import RegisterForm from '../components/Register.vue'
import LoginForm from '../components/Login.vue'

const routes = [
    {
        path: '/',
        redirect: '/products'
    },
    {
        path: '/login',
        name: 'login',
        component: LoginForm
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterForm
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
]

// const router = createRouter({
//     history: createWebHistory(import.meta.env.BASE_URL),
//     routes
// })

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

export default router
