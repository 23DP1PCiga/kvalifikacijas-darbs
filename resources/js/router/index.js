import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Register from '../components/Register.vue'
import Books from '../components/Gramatas.vue'
import BookPage from '../components/BookPage.vue'

import Saved from '../components/Saved.vue'
import MyComments from '../components/MyComments.vue'
import MyRatings from '../components/MyRatings.vue'


const routes = [
  {
    path: '/',
    component: Home,
  },
  {
    path: '/register',
    component: Register,
  },
   {
    path: '/books',
    component: Books
  },
  {
  path: '/books/:id',
  component: BookPage
  },
  { 
    path: '/saved', 
    component: Saved },

  { 
    path: '/comments', 
    component: MyComments },

  { 
    path: '/ratings', 
    component: MyRatings }
]


const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router