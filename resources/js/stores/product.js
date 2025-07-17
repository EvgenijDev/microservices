import { defineStore } from 'pinia'
import axios from 'axios'

export const useProductStore = defineStore('product', {
    state: () => ({
        products: [],
        loading: false,
        error: null
    }),
    actions: {
        async fetchProducts () {
            this.loading = true
            try {
                const response = await axios.get('/api/v1/products')
                this.products = response.data
            } catch (error) {
                this.error = error
            } finally {
                this.loading = false
            }
        },
        async createProduct (productData) {
            this.loading = true
            try {
                const response = await axios.post(
                    '/api/v1/products',
                    productData
                )
                this.products.push(response.data)
                return response.data
            } catch (error) {
                this.error = error
                throw error
            } finally {
                this.loading = false
            }
        },
        async updateProduct ({ id, ...productData }) {
            this.loading = true
            try {
                const response = await axios.put(
                    `/api/v1/products/${id}`,
                    productData
                )
                const index = this.products.findIndex(p => p.id === id)
                if (index !== -1) {
                    this.products[index] = response.data
                }
                return response.data
            } catch (error) {
                this.error = error
                throw error
            } finally {
                this.loading = false
            }
        },
        async deleteProduct (id) {
            this.loading = true
            try {
                await axios.delete(`/api/v1/products/${id}`)
                this.products = this.products.filter(p => p.id !== id)
            } catch (error) {
                this.error = error
                throw error
            } finally {
                this.loading = false
            }
        },
        async getProductById (id) {
            const response = await axios.get(`/api/v1/products/${id}`)
            return response.data
        }
    }
})
