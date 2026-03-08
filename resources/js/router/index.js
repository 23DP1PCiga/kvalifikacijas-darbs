import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Register from '../components/Register.vue'
import Books from '../components/Gramatas.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
  },
   {
    path: '/books',
    component: Books
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router