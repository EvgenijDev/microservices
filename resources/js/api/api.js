import axios from 'axios';
import { useAuthStore } from '../stores/auth'

const base = (import.meta.env.VITE_APP_URL || window.location.origin) + '/api/v1'
const api = axios.create({
    baseURL: base
});

// Добавляем интерцептор для токена
api.interceptors.request.use((config) => {
    // берем токен из Pinia, затем fallback к localStorage
    let token
    try {
        const store = useAuthStore()
        token = store?.token
    } catch (_) {
        // Pinia может быть не инициализирована в некоторых контекстах
    }
    token = token || localStorage.getItem('api_token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

api.interceptors.response.use(
    (r) => r,
    (error) => {
        if (error?.response?.status === 401) {
            // необязательный редирект при 401
            if (window.location.pathname !== '/login') {
                window.location.href = '/login'
            }
        }
        return Promise.reject(error)
    }
)

export default api;