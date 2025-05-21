import './assets/main.scss';
import '@fontawesome/fontawesome-free/css/all.min.css';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import $  from 'jquery'
import App from './App.vue';
import router from './router';
import * as utils from '../src/utils/main.js';

window.$      = $
window.jQuery = $
window.utils  = utils;

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
