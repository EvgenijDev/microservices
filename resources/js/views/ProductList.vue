<template>
    <ImportProgressModal 
        :importId="importId"
        @close="productStore.clearImportId()"
        @success="productStore.fetchProducts()"
    />
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Products</h1>

            <form
                @submit.prevent="searchProduct"
                class="flex gap-2 items-center"
            >
                <div class="relative w-64">
                    <input
                        v-model="form.query"
                        type="search"
                        placeholder="Search"
                        class="block w-full p-2 ps-8 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <!-- Лупа слева -->
                    <svg
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 20"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                        />
                    </svg>
                    <!-- Крестик справа -->
                    <button
                        v-if="form.query"
                        type="button"
                        @click="resetSearch"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                        ✖
                    </button>
                </div>

                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                >
                    Search
                </button>

                <button
                    type="button"
                    @click="resetSearch"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                >
                    Сбросить
                </button>
            </form>

            <div class="flex items-center mb-4">
                <input
                    @change="searchProduct"
                    v-model="form.inStock"
                    id="default-checkbox"
                    type="checkbox"
                    value=""
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <label
                    for="default-checkbox"
                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                    >Только товары в наличии</label
                >
            </div>

            <router-link
                :to="{ name: 'product.create' }"
                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                Add Product
            </router-link>
            <input
                ref="fileInput"
                type="file"
                id="file-input"
                accept=".xlsx,.xls,.csv"
                class="file-input"
                @change="onFileSelected"
            />
            <button
                type="button"
                @click="fileInput?.click()"
                class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
            >
                Import Products
            </button>
        </div>

        <div v-if="loading" class="text-gray-600">Loading...</div>
        <div v-else-if="error" class="text-red-600">{{ error.message }}</div>

        <div
            v-else
            class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm"
        >
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            ID
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            Name
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            Price
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            Stock
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            Image
                        </th>
                        <th
                            class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr
                        v-for="product in products.data"
                        :key="product.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ product.id }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                            {{ product.name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ product.price }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ product.stock }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <img
                                v-if="product.thumb_path || product.image_path"
                                :src="imageUrl(product)"
                                alt="Image"
                                class="h-12 w-12 object-cover rounded"
                            />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <router-link
                                    :to="{
                                        name: 'product.edit',
                                        params: { id: product.id }
                                    }"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    Edit
                                </router-link>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="products.meta && products.meta.last_page > 1"
            :current-page="products.meta.current_page"
            :last-page="products.meta.last_page"
            @page-changed="handlePageChange"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useProductStore } from '../stores/product'
import { storeToRefs } from 'pinia'
import Pagination from "../components/Pagination.vue";
import ImportProgressModal from "../components/ImportProgressModal.vue";

const fileInput = ref(null)
const productStore = useProductStore()
const { products, loading, error, importId } = storeToRefs(productStore)
const form = ref({
    query: '',
    inStock: false
})
onMounted(() => {
    productStore.fetchProducts();
    if (window.Echo) {
        window.Echo.channel('products')
            .listen('.product.changed', (e) => {
                productStore.fetchProducts();
            });
    }
});

onUnmounted(() => {
    if (window.Echo) {
        window.Echo.leave('products');
    }
});

const handlePageChange = (page) => {
  productStore.fetchProducts(page) // передаём номер страницы
}

const deleteProduct = id => {
    if (confirm('Are you sure you want to delete this product?')) {
        productStore.deleteProduct(id)
    }
}

const onFileSelected = (event) => {
    const file = event.target.files?.[0]
    if (!file) return
    productStore.importProducts(file)
    event.target.value = ''
}

const searchProduct = () => {
    if(form.value.inStock || form.value.query){
        productStore.searchProduct(form.value.query, form.value.inStock)
    } else {
        productStore.fetchProducts()
    }
    
}

const resetSearch = () => {
    form.value.query = ''
    form.value.inStock = false
    productStore.fetchProducts()
}

const imageUrl = product => {
    const base = import.meta.env.VITE_APP_URL || window.location.origin
    const path = product.thumb_path || product.image_path
    // files stored on "public" disk → served as /storage/{path}
    return `${base}/storage/${path}`
}

</script>

<style scoped>
    .file-input {
        display: none;
    }
</style>