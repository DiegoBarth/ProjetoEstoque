import { createRouter, createWebHistory } from 'vue-router'
import Painel from '../views/Painel.vue'
import Login from '../views/Login.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login
    },
    {
      path: '/inicio',
      name: 'Inicio',
      component: Painel,
    },    
  ],
})

export default router
