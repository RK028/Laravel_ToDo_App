<template>
  <div id="app">
    <nav class="bg-gray-800 p-4 text-white flex justify-between items-center">
      <div>
        <router-link to="/todos" v-if="isAuthenticated" class="mr-4">ToDo List</router-link>
        <router-link to="/" v-if="!isAuthenticated" class="mr-4">Login</router-link>
        <router-link to="/register" v-if="!isAuthenticated" class="mr-4">Register</router-link>
        <button v-if="isAuthenticated" @click="logout" class="bg-red-500 px-4 py-2 rounded">Logout</button>
      </div>
    </nav>
    <router-view />
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      isAuthenticated: !!localStorage.getItem('token'), // Check if the token exists
    };
  },
  methods: {
    async logout() {
    try {
        await axios.post('http://localhost:8000/api/logout', {}, { // Pass an empty object for the data
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}` // Correct placement of headers
            }
        });
        localStorage.removeItem('token'); // Remove the token from local storage
        this.isAuthenticated = false; // Update the authentication status
        this.$router.push('/'); // Redirect to the login page
    } catch (error) {
        console.error("Logout failed:", error);
        if (error.response && error.response.status === 401) {
            // Handle unauthorized error, maybe redirect to login
            this.$router.push('/');
        }
    }
}

  }
};
</script>

<style scoped>
/* You can add your custom styles here */
</style>
