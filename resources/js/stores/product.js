import { defineStore } from 'pinia'
import axios from 'axios'
import api from '../api/api'

export const useProductStore = defineStore('product', {
    state: () => ({
        products: [],
        loading: false,
        error: null
    }),
    actions: {
        async fetchProducts (page=1) {
            this.loading = true
            try {
                const response = await api.get(`/products?page=${page}`)
                // const response = await axios.get('/api/v1/products')
                this.products = {
                    data: response.data.data,
                    meta: response.data.meta,
                    links: response.data.links
                }
            } catch (error) {
                this.error = error
            } finally {
                this.loading = false
            }
        },
        async createProduct (productData) {
            this.loading = true
            try {
                const response = await api.post('/products', productData)
                this.products.push(response.data)
                return response.data
            } catch (error) {
                this.error = error
                throw error
            } finally {
                this.loading = false
            }
        },
        async createProductFormData (formData) {
            this.loading = true
            try {
                const response = await api.post('/products', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
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
                const response = await api.put(`/products/${id}`, productData)
                const index = this.products.data.findIndex(p => p.id === id)
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
        async updateProductFormData (id, formData) {
            this.loading = true
            try {
                const response = await api.post(
                    `/products/${id}?_method=PUT`,
                    formData,
                    { headers: { 'Content-Type': 'multipart/form-data' } }
                )
                const index = this.products.data.findIndex(p => p.id === id)
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
                await api.delete(`/products/${id}`)
                this.products = this.products.filter(p => p.id !== id)
            } catch (error) {
                this.error = error
                throw error
            } finally {
                this.loading = false
            }
        },
        async getProductById (id) {
            const response = await api.get(`/products/${id}`)
            return response.data
        },
        async searchProduct (q, inStock) {
            this.loading = true
            try {
                const params = {}
        
                if (q && q.trim() !== '') {
                    params.q = q
                }
        
                params.inStock = inStock   // ← всегда передаём true/false
        
                const response = await api.get('/search', { params })
                this.products = {
                    data: response.data.data,
                    meta: response.data.meta,
                    links: response.data.links
                }
            } catch (error) {
                this.error = error
            } finally {
                this.loading = false
            }
        }
        
    }
})
