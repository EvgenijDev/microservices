<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Products</h1>
            <router-link :to="{ name: 'product.create' }" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Add Product
            </router-link>
        </div>

        <div v-if="loading" class="text-gray-600">Loading...</div>
        <div v-else-if="error" class="text-red-600">{{ error.message }}</div>

        <div v-else class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Stock</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700">{{ product.id }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ product.name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ product.price }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ product.stock }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <router-link 
                                    :to="{ name: 'product.edit', params: { id: product.id } }" 
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Edit
                                </router-link>
                                <button 
                                    @click="deleteProduct(product.id)" 
                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
</template>

<script setup>
import { onMounted } from 'vue';
import { useProductStore } from '../stores/product';
import { storeToRefs } from 'pinia'

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