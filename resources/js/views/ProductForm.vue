<template>
    <div class="space-y-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            <span v-if="!productId">Create Product</span>
            <span v-else>Edit Product</span>
        </h1>
        
        <form @submit.prevent="submitForm" class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        v-model="form.name" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                </div>
                
                <div class="col-span-1 sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea 
                        id="description" 
                        v-model="form.description" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        rows="3"
                    ></textarea>
                </div>
                
                <div class="col-span-1 sm:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                    <div class="mt-1 flex items-center gap-4">
                        <img v-if="previewUrl || existingImageUrl" :src="previewUrl || existingImageUrl" alt="preview" class="h-16 w-16 object-cover rounded border" />
                        <input 
                            id="image" 
                            type="file" 
                            accept="image/*"
                            @change="onFileChange"
                            class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-blue-700 hover:file:bg-blue-100"
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Если файл не выбран — останется текущее изображение.</p>
                </div>

                <div class="col-span-1">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input 
                        type="number" 
                        id="price" 
                        v-model="form.price" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        min="0"
                        step="0.01"
                        required>
                </div>
                
                <div class="col-span-1">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                    <input 
                        type="number" 
                        id="stock" 
                        v-model="form.stock" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        min="0"
                        required>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-2">
                <button type="button" @click="$router.push({ name: 'products' })" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" :disabled="loading">
                    {{ loading ? 'Saving...' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductStore } from '../stores/product';
import { storeToRefs } from 'pinia'

const route = useRoute();
const router = useRouter();
const productStore = useProductStore();
const { loading } = storeToRefs(productStore);

const productId = route.params.id;
const form = ref({
    name: '',
    description: '',
    price: 0,
    stock: 0,
    image: null,
    image_path: null,
    thumb_path: null
});
const previewUrl = ref('')

onMounted(async () => {
    if (productId) {
        const product = await productStore.getProductById(productId);
        form.value = { ...product, image: null };
    }
});

const submitForm = async () => {
    try {
        const data = new FormData()
        data.append('name', form.value.name)
        data.append('description', form.value.description || '')
        data.append('price', form.value.price)
        data.append('stock', form.value.stock)
        if (form.value.image) data.append('image', form.value.image)

        if (productId) {
            await productStore.updateProductFormData(productId, data)
        } else {
            await productStore.createProductFormData(data)
        }
        router.push({ name: 'products' });
    } catch (error) {
        console.error('Error saving product:', error);
    }
};

const onFileChange = (e) => {
    const files = e.target.files
    const file = files && files[0] ? files[0] : null
    form.value.image = file
    if (file) {
        previewUrl.value = URL.createObjectURL(file)
    } else {
        previewUrl.value = ''
    }
}

const existingImageUrl = computed(() => {
    const base = import.meta.env.VITE_APP_URL || window.location.origin
    const path = form.value.thumb_path || form.value.image_path
    return path ? `${base}/storage/${path}` : ''
})
</script>