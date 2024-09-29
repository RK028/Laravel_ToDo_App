<template>
    <div class="max-w-md mx-auto p-4">
      <h1 class="text-xl font-bold mb-4">ToDo Application</h1>
      
      <form @submit.prevent="editingTodo ? updateTodo() : addTodo()" class="mb-4">
        <input 
          v-model="newTodo.title" 
          type="text" 
          placeholder="Title" 
          class="border border-gray-300 p-2 w-full mb-2"
          required
        />
        <textarea 
          v-model="newTodo.description" 
          placeholder="Description" 
          class="border border-gray-300 p-2 w-full mb-2"
          required
        ></textarea>
        <button 
          type="submit" 
          class="bg-blue-500 text-white py-2 px-4 rounded"
        >
          {{ editingTodo ? 'Update Todo' : 'Add Todo' }}
        </button>
      </form>
  
      <ul>
        <li v-for="todo in todos" :key="todo.id" class="border-b mb-2 pb-2">
          <h2 class="font-bold">{{ todo.title }}</h2>
          <p>{{ todo.description }}</p>
          <div class="flex justify-between">
            <button @click="editTodoDetails(todo)" class="text-blue-500">Edit</button>
            <button @click="deleteTodo(todo.id)" class="text-red-500">Delete</button>
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        todos: [],
        newTodo: {
          title: '',
          description: '',
        },
        editingTodo: null,
      };
    },
    methods: {
      async fetchTodos() {
        try {
          const response = await axios.get('http://localhost:8000/api/todos', {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }
          });
          this.todos = response.data;
        } catch (error) {
          console.error('Error fetching todos:', error);
        }
      },
      async addTodo() {
        try {
          const response = await axios.post('http://localhost:8000/api/todos', this.newTodo, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }
          });
          this.todos.push(response.data);
          this.newTodo = { title: '', description: '' }; // Reset the form
        } catch (error) {
          console.error('Error adding todo:', error);
        }
      },
      editTodoDetails(todo) {
        this.editingTodo = todo;
        this.newTodo = { ...todo }; // Pre-fill the form for editing
      },
      async updateTodo() {
        try {
          const response = await axios.put(`http://localhost:8000/api/todos/${this.editingTodo.id}`, this.newTodo, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }
          });
          const index = this.todos.findIndex(t => t.id === this.editingTodo.id);
          this.todos.splice(index, 1, response.data); // Update the todo in the array
          this.editingTodo = null; // Reset editing
          this.newTodo = { title: '', description: '' }; // Reset the form
        } catch (error) {
          console.error('Error updating todo:', error);
        }
      },
      async deleteTodo(id) {
        try {
          await axios.delete(`http://localhost:8000/api/todos/${id}`, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }
          });
          this.todos = this.todos.filter(todo => todo.id !== id);
        } catch (error) {
          console.error('Error deleting todo:', error);
        }
      },
    },
    mounted() {
      this.fetchTodos();
    },
  };
  </script>
  
  <style scoped>
  </style>
  