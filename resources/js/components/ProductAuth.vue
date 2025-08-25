
<template>
   
</template>
<script>
import authService from '../services/auth.service';
import productService from '../services/product.service';

export default {
    data() {
        return {
            email: '',
            password: '',
            products: [],
            newProduct: {
                name: '',
                price: 0,
                description: ''
            }
        };
    },
    methods: {
        async login() {
            try {
                await authService.login(this.email, this.password);
                this.loadProducts();
            } catch (error) {
                console.error('Login failed:', error);
            }
        },
        async logout() {
            await authService.logout();
        },
        async loadProducts() {
            try {
                const response = await productService.getProducts();
                this.products = response.data;
            } catch (error) {
                console.error('Failed to load products:', error);
            }
        },
        async createProduct() {
            try {
                await productService.createProduct(this.newProduct);
                this.newProduct = { name: '', price: 0, description: '' };
                this.loadProducts();
            } catch (error) {
                console.error('Failed to create product:', error);
            }
        }
    },
    created() {
        if (authService.isAuthenticated()) {
            this.loadProducts();
        }
    }
};
</script>