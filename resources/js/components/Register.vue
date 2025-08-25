<template>
  <div>
    <h2>Register</h2>
    <form @submit.prevent="register">
      <div>
        <label>Name:</label>
        <input v-model="name" type="text" required>
      </div>
      <div>
        <label>Email:</label>
        <input v-model="email" type="email" required>
      </div>
      <div>
        <label>Password:</label>
        <input v-model="password" type="password" required>
      </div>
      <div>
        <label>Confirm Password:</label>
        <input v-model="password_confirmation" type="password" required>
      </div>
      <button type="submit">Register</button>
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
      password: '',
      password_confirmation: ''
    };
  },
  methods: {
    async register() {
      try {
        await authService.register(
          this.name,
          this.email,
          this.password,
          this.password_confirmation
        );
        this.$router.push('/'); // Перенаправление после успешной регистрации
      } catch (error) {
        console.error('Registration failed:', error.response.data);
      }
    }
  }
};
</script>