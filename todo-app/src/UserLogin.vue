<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Login</h1>
    
    <form @submit.prevent="login">
      <input 
        v-model="email" 
        type="email" 
        placeholder="Email" 
        class="border border-gray-300 p-2 w-full mb-2"
        required
      />
      <div class="relative mb-2">
        <input 
          v-model="password" 
          :type="passwordFieldType" 
          placeholder="Password" 
          class="border border-gray-300 p-2 w-full"
          required
        />
        <button type="button" @click="togglePasswordVisibility" class="absolute right-2 top-2">
          <span v-if="passwordFieldType === 'password'">👁️</span>
          <span v-else>🙈</span>
        </button>
      </div>
      <button 
        type="submit" 
        class="bg-blue-500 text-white py-2 px-4 rounded"
      >
        Login
      </button>
    </form>
    
    <p v-if="error" class="text-red-500">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserLogin', // Updated to multi-word name
  data() {
    return {
      email: '',
      password: '',
      error: '',
      passwordFieldType: 'password', // Initial field type for password
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email,
          password: this.password,
        });
        console.log(response);
        
        localStorage.setItem('token', response.data.access_token); // Save token
        this.$router.push('/todos'); // Redirect to todo page
      } catch (error) {
        this.error = error.response.data.message || 'Login failed';
        console.error('Error logging in:', error);
      }
    },
    togglePasswordVisibility() {
      this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password';
    },
  },
};
</script>

<style scoped>
</style>
