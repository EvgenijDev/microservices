import api from '../api/api'

export default {
    login (email, password) {
        return api.post('/login', { email, password }).then(response => {
            localStorage.setItem('api_token', response.data.token)
            return response
        })
    },
    logout () {
        return api.post('/logout').then(() => {
            localStorage.removeItem('api_token')
        })
    },
    getToken () {
        return localStorage.getItem('api_token')
    },
    isAuthenticated () {
        return !!localStorage.getItem('api_token')
    },
    register (name, email, password, password_confirmation) {
        return api
            .post('/register', { name, email, password, password_confirmation })
            .then(response => {
                localStorage.setItem('api_token', response.data.token)
                return response
            })
    }
}
