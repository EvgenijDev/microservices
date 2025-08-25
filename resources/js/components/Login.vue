<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div>
        <label>Email:</label>
        <input v-model="email" type="email" required>
      </div>
      <div>
        <label>Password:</label>
        <input v-model="password" type="password" required>
      </div>
      <button type="submit">Login</button>
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