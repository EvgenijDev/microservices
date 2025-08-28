import api from '../api/api'
import { useAuthStore } from '../stores/auth'

export default {
    login (email, password) {
        const authStore = useAuthStore()
        return api.post('/login', { email, password }).then(async response => {
            authStore.setToken(response.data.token)
            // получить профиль пользователя
            const me = await api.get('/me')
            authStore.setUser(me.data)
            return response
        })
    },
    logout () {
        const authStore = useAuthStore()
        return api.post('/logout').then(() => {
            authStore.clearToken()
        })
    },
    getToken () {
        const authStore = useAuthStore()
        return authStore.token
    },
    isAuthenticated () {
        const authStore = useAuthStore()
        return authStore.isAuthenticated
    },
    register (name, email, password, password_confirmation) {
        const authStore = useAuthStore()
        return api
            .post('/register', { name, email, password, password_confirmation })
            .then(async response => {
                authStore.setToken(response.data.token)
                // при регистрации backend уже возвращает user, если нет — добираем /me
                if (response.data.user) {
                    authStore.setUser(response.data.user)
                } else {
                    const me = await api.get('/me')
                    authStore.setUser(me.data)
                }
                return response
            })
    }
}
