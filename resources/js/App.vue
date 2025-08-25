<template>
    <div id="app">
        <nav>
            <router-link to="/">Home</router-link> |
            <router-link to="/products">Products</router-link> |
            <router-link v-if="!isAuthenticated" to="/register">Register</router-link> |
            <router-link v-if="!isAuthenticated" to="/login">Login</router-link>
            <router-view />
            <button
                v-if="isAuthenticated"
                @click="logout"
                class="logout-button"
            >
                Выйти
            </button>
        </nav>

    </div>
</template>

<script>
import authService from './services/auth.service'
export default {
    name: 'App',
    computed: {
        isAuthenticated () {
            return authService.isAuthenticated()
        }
    },
    methods: {
        async logout () {
            try {
                await authService.logout()
                this.$router.push('/login') // Перенаправление после выхода
            } catch (error) {
                console.error('Logout failed:', error)
            }
        }
    }
}
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}
</style>
