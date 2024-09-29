<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Register</h1>
    
    <form @submit.prevent="register">
      <input 
        v-model="name" 
        type="text" 
        placeholder="Name" 
        class="border border-gray-300 p-2 w-full mb-2"
        required
      />
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
      <div class="relative mb-2">
        <input 
          v-model="confirmPassword" 
          :type="confirmPasswordFieldType" 
          placeholder="Confirm Password" 
          class="border border-gray-300 p-2 w-full"
          required
        />
        <button type="button" @click="toggleConfirmPasswordVisibility" class="absolute right-2 top-2">
          <span v-if="confirmPasswordFieldType === 'password'">👁️</span>
          <span v-else>🙈</span>
        </button>
      </div>
      <button 
        type="submit" 
        class="bg-blue-500 text-white py-2 px-4 rounded"
      >
        Register
      </button>
    </form>
    
    <p v-if="error" class="text-red-500">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserRegister', // Updated to multi-word name
  data() {
    return {
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      error: '',
      passwordFieldType: 'password', // Initial field type for password
      confirmPasswordFieldType: 'password', // Initial field type for confirm password
    };
  },
  methods: {
    async register() {
      try {
        await axios.post('http://localhost:8000/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword,
        });
        alert('Registration successful!'); // Show alert or redirect
        this.$router.push('/'); // Redirect to login page after registration
      } catch (error) {
        this.error = error.response.data.message || 'Registration failed';
        console.error('Error registering:', error);
      }
    },
    togglePasswordVisibility() {
      this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password';
    },
    toggleConfirmPasswordVisibility() {
      this.confirmPasswordFieldType = this.confirmPasswordFieldType === 'password' ? 'text' : 'password';
    },
  },
};
</script>

<style scoped>
</style>
