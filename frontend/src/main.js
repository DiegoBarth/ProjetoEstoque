import './assets/main.scss';
import '@fontawesome/fontawesome-free/css/all.min.css';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import $  from 'jquery'

import App from './App.vue'
import router from './router'

window.$ = $
window.jQuery = $

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
