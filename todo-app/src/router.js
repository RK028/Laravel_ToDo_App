import { createRouter, createWebHistory } from 'vue-router';
import TodoApp from './TodoApp.vue';
import UserLogin from './UserLogin.vue';
import UserRegister from './UserRegister.vue';

// Create the router instance
const routes = [
  { path: '/', component: UserLogin },
  { path: '/register', component: UserRegister },
  { path: '/todos', component: TodoApp },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
