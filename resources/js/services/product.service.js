import api from '../api/api';

export default {
    getProducts() {
        return api.get('/products');
    },
    getProduct(id) {
        return api.get(`/products/${id}`);
    },
    createProduct(productData) {
        return api.post('/products', productData);
    },
    updateProduct(id, productData) {
        return api.put(`/products/${id}`, productData);
    },
    deleteProduct(id) {
        return api.delete(`/products/${id}`);
    }
};