import axios from 'axios';
import Cookies from 'js-cookie';
import router from '@/router';

const api = axios.create({
   baseURL: 'http://localhost:8000',
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
      return response;
   },
   error => {
      utils.removerLoading();

      if(error.response && error.response.status === 401) {
         if(router.currentRoute.value.name !== 'Login') {
            router.push({ name: 'Login' });
         }

         return error.response;
      }
      
      return Promise.reject(error);
   }
);

export default api;