import axios from 'axios';

const api = axios.create({
    baseURL: import.meta.env.VITE_APP_URL + '/api/v1',
    withCredentials: true,
});

// Добавляем интерцептор для токена
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('api_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;