import { createRouter, createWebHistory } from 'vue-router';
import Painel from '../views/Painel.vue';
import Produto from '../views/Produto.vue';
import Login from '../views/Login.vue';
import Cliente from'../views/Cliente.vue';
import Fornecedor from'../views/Fornecedor.vue';
import Venda from'../views/Venda.vue';
import Relatorio from'../views/Relatorio.vue';
import Usuario from '../views/Usuario.vue'
import Meta from'../views/Meta.vue';

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
         component: Painel
      },
      {
         path: '/cliente',
         name: 'Cliente',
         component: Cliente
      },
      {
         path: '/fornecedor',
         name: 'Fornecedor',
         component: Fornecedor
      },
      {
         path: '/produto',
         name: 'Produto',
         component: Produto
      },
      {
         path: '/usuario',
         name: 'Usuário',
         component: Usuario
      },
      {
         path: '/venda',
         name: 'Venda',
         component: Venda
      },
      {
         path: '/relatório',
         name: 'Relatório',
         component: Relatorio
      },
      {
         path: '/meta',
         name: 'Meta',
         component: Meta
      },
   ],
});

export default router;