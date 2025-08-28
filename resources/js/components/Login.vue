<template>
  <div class="max-w-md mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
    <h2 class="text-xl font-semibold text-gray-800">Login</h2>
    <form @submit.prevent="login" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input v-model="email" type="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input v-model="password" type="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>
      <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
    </form>
  </div>
</template>

<script>
import authService from '../services/auth.service';

export default {
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    async login() {
      try {
        await authService.login(
          this.email,
          this.password,
        );
        this.$router.push('/'); // Перенаправление после успешной регистрации
      } catch (error) {
        console.error('Login failed:', error.response.data);
      }
    }
  }
};
</script>