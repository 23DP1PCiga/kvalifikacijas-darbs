import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Register from '../components/Register.vue'
import Books from '../components/Gramatas.vue'
import BookPage from '../components/BookPage.vue'

import Saved from '../components/Saved.vue'
import MyComments from '../components/MyComments.vue'
import Admin from '../components/Admin.vue'
import AdminCreate from '../components/AdminCreate.vue'
import AdminEdit from '../components/AdminEdit.vue'
import AdminStats from '../components/AdminStats.vue'
import Boards from '../components/Boards.vue'
import BoardPage from '../components/BoardPage.vue'


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
    component: MyComments 
  },
  {
    path: '/admin',
    component: Admin
  },
  {
    path: '/admin/create',
    component: AdminCreate
  },
  {
    path: '/admin/edit/:id',
    component: AdminEdit
  },
  {
    path: '/admin/stats',
    component: AdminStats
  },
  {
    path: '/boards',
    component: Boards
  },
  {
    path: '/boards/:id',
    component: BoardPage
  }
]


const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router