<template>
    <div id="app" class="min-h-screen bg-gray-50 text-gray-900">
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between">
                    <nav class="flex items-center text-sm font-medium text-gray-700">
                        <router-link v-slot="{ isActive }" to="/" class="px-2">
                            <span :class="isActive ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'">Home</span>
                        </router-link>
                        <span class="px-1 text-gray-300">|</span>
                        <router-link v-slot="{ isActive }" to="/products" class="px-2">
                            <span :class="isActive ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'">Products</span>
                        </router-link>
                        <span class="px-1 text-gray-300">|</span>
                        <router-link v-if="!isAuthenticated" v-slot="{ isActive }" to="/register" class="px-2">
                            <span :class="isActive ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'">Register</span>
                        </router-link>
                        <span v-if="!isAuthenticated" class="px-1 text-gray-300">|</span>
                        <router-link v-if="!isAuthenticated" v-slot="{ isActive }" to="/login" class="px-2">
                            <span :class="isActive ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'">Login</span>
                        </router-link>
                    </nav>

                    <div class="relative" v-if="isAuthenticated">
                        <button @click="toggleDropdown" class="flex items-center gap-2 focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                {{ userInitials }}
                            </div>
                            <svg class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black/5 overflow-hidden z-10">
                            <div class="px-4 py-3">
                                <p class="text-sm text-gray-500">Signed in as</p>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ user?.name || user?.email }}</p>
                            </div>
                            <div class="border-t border-gray-100" />
                            <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <router-view />
        </main>
    </div>
</template>

<script>
import authService from './services/auth.service'
import { useAuthStore } from './stores/auth'
import { computed, ref } from 'vue'

export default {
    name: 'App',
    setup () {
        const authStore = useAuthStore()
        const isAuthenticated = computed(() => authStore.isAuthenticated)
        const user = computed(() => authStore.user)
        const userInitials = computed(() => {
            const name = authStore.user?.name || authStore.user?.email || ''
            if (!name) return ''
            const parts = name.split(/\s|\./).filter(Boolean)
            const first = parts[0]?.[0] || ''
            const second = parts[1]?.[0] || ''
            return (first + second).toUpperCase()
        })
        const dropdownOpen = ref(false)
        const toggleDropdown = () => { dropdownOpen.value = !dropdownOpen.value }

        const logout = async () => {
            try {
                await authService.logout()
                dropdownOpen.value = false
                window.location.href = '/login'
            } catch (error) {
                console.error('Logout failed:', error)
            }
        }

        return { isAuthenticated, user, userInitials, dropdownOpen, toggleDropdown, logout }
    }
}
</script>

<style>
</style>
