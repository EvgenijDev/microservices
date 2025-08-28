import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('api_token') || null,
        user: null
    }),
    getters: {
        isAuthenticated: (state) => !!state.token
    },
    actions: {
        setToken (token) {
            this.token = token
            if (token) {
                localStorage.setItem('api_token', token)
            } else {
                localStorage.removeItem('api_token')
            }
        },
        setUser (user) {
            this.user = user
        },
        clearToken () {
            this.setToken(null)
            this.user = null
        }
    }
})


