<template>
    <div>
        <h1>Products</h1>
        <router-link :to="{ name: 'product.create' }" class="btn btn-primary">
            Add Product
        </router-link>
        
        <div v-if="loading">Loading...</div>
        <div v-else-if="error">{{ error.message }}</div>
        <table v-else class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.stock }}</td>
                    <td>
                        <router-link 
                            :to="{ name: 'product.edit', params: { id: product.id } }" 
                            class="btn btn-sm btn-info">
                            Edit
                        </router-link>
                        <button 
                            @click="deleteProduct(product.id)" 
                            class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useProductStore } from '../stores/product';

const productStore = useProductStore();
const { products, loading, error } = storeToRefs(productStore);

onMounted(() => {
    productStore.fetchProducts();
});

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        productStore.deleteProduct(id);
    }
};
</script>