<template>
    <div>
        <h1 v-if="!productId">Create Product</h1>
        <h1 v-else>Edit Product</h1>
        
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="name">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    v-model="form.name" 
                    class="form-control"
                    required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    v-model="form.description" 
                    class="form-control"></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Price</label>
                <input 
                    type="number" 
                    id="price" 
                    v-model="form.price" 
                    class="form-control"
                    min="0"
                    step="0.01"
                    required>
            </div>
            
            <div class="form-group">
                <label for="stock">Stock</label>
                <input 
                    type="number" 
                    id="stock" 
                    v-model="form.stock" 
                    class="form-control"
                    min="0"
                    required>
            </div>
            
            <button type="submit" class="btn btn-primary" :disabled="loading">
                {{ loading ? 'Saving...' : 'Save' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductStore } from '../stores/product';

const route = useRoute();
const router = useRouter();
const productStore = useProductStore();
const { loading } = storeToRefs(productStore);

const productId = route.params.id;
const form = ref({
    name: '',
    description: '',
    price: 0,
    stock: 0
});

onMounted(async () => {
    if (productId) {
        const product = await productStore.getProductById(productId);
        form.value = { ...product };
    }
});

const submitForm = async () => {
    try {
        if (productId) {
            await productStore.updateProduct({ id: productId, ...form.value });
        } else {
            await productStore.createProduct(form.value);
        }
        router.push({ name: 'products' });
    } catch (error) {
        console.error('Error saving product:', error);
    }
};
</script>