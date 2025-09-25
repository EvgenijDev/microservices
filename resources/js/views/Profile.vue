<template>
    <div class="max-w-xl mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-2">
        <h1 class="text-xl font-semibold text-gray-800 mb-2">Profile</h1>
        <div v-if="loading" class="text-gray-500">Loading...</div>
        <div v-else-if="error" class="text-red-600">{{ error }}</div>
        <div v-else>
            <p><span class="font-medium">Name:</span> {{ profile?.name }}</p>
            <p><span class="font-medium">Email:</span> {{ profile?.email }}</p>
            <p v-if="profile?.gender"><span class="font-medium">Gender:</span> {{ profile?.gender }}</p>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import authService from '../services/auth.service'

const profile = ref(null)
const loading = ref(true)
const error = ref('')

onMounted(async () => {
    try {
        const { data } = await authService.getProfile()
        profile.value = data
    } catch (e) {
        error.value = e?.response?.data?.message || e?.message || 'Failed to load profile'
    } finally {
        loading.value = false
    }
})
</script>

<style>
</style>


