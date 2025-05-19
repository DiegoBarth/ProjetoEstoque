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
   const token = Cookies.get('XSRF-TOKEN');
   if(token) {
      config.headers['X-XSRF-TOKEN'] = token;
   }
   return config;
});

api.interceptors.response.use(
   response => response,
   error    => {
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