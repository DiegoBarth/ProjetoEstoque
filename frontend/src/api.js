import axios from 'axios';
import Cookies from 'js-cookie';
import router from '@/router';
import { alerta } from './utils/main';

const api = axios.create({
   baseURL: 'http://localhost',
   withCredentials: true,
   headers: {
      'Accept'      : 'application/json'
   }
});

api.interceptors.request.use(config => {
   utils.adicionarLoading(); 

   const oToken = Cookies.get('XSRF-TOKEN');

   if(oToken) {
      config.headers['X-XSRF-TOKEN'] = oToken;
   }

   return config;
},
error => {
   utils.removerLoading();
   return Promise.reject(error);
});

api.interceptors.response.use(
   response => {
      utils.removerLoading();

      if(response.data?.sMensagem && router.currentRoute.value.name !== 'Login') {
         utils.alerta(response.data.sMensagem);
      }


      return response;
   },
   error => {
      utils.removerLoading();

      if(error.response?.data?.sMensagem && router.currentRoute.value.name !== 'Login') {
         utils.alerta(error.response.data.sMensagem, 'error');
      }

      if(error.response && error.response.status === 401) {
         if(router.currentRoute.value.name !== 'Login') {
            router.push({ name: 'Login' });
         }

         return error.response;
      }
      
      if(error.response.data.message) {
         alerta(error.response.data.message, 'error');
      }
      
      return Promise.reject(error);
   }
);

export default api;