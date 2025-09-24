<template>
  <div class="max-w-md mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
    <h2 class="text-xl font-semibold text-gray-800">Register</h2>
    <form @submit.prevent="register" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input v-model="name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input v-model="email" type="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Gender</label>
        <select v-model="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <option value="" disabled>Select gender</option>
          <option value="male">male</option>
          <option value="female">female</option>
        </select>
      </div>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <div class="mt-1 relative">
            <input :type="showPassword ? 'text' : 'password'" v-model="password" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10">
            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700 focus:outline-none">
              <span v-if="!showPassword">Показать</span>
              <span v-else>Скрыть</span>
            </button>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <div class="mt-1 relative">
            <input :type="showConfirmPassword ? 'text' : 'password'" v-model="password_confirmation" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10">
            <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700 focus:outline-none">
              <span v-if="!showConfirmPassword">Показать</span>
              <span v-else>Скрыть</span>
            </button>
          </div>
        </div>
      </div>
      <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Register</button>
    </form>
  </div>
</template>

<script>
import authService from '../services/auth.service';

export default {
  data() {
    return {
      name: '',
      email: '',
      gender: '',
      password: '',
      password_confirmation: '',
      showPassword: false,
      showConfirmPassword: false
    };
  },
  methods: {
    async register() {
      try {
        await authService.register(
          this.name,
          this.email,
          this.gender,
          this.password,
          this.password_confirmation
        );
        this.$router.push('/'); // Перенаправление после успешной регистрации
      } catch (error) {
        console.error('Registration failed:', error.response?.data || error);
      }
    }
  }
};
</script>