import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import '@fortawesome/fontawesome-free/css/all.css';
import axios from 'axios';
import './assets/main.css' 


//axios.defaults.baseURL = 'http://localhost/api'; // same base as APP_URL in Laravel

axios.defaults.baseURL = 'https://ac44-110-39-134-182.ngrok-free.app/api';

createApp(App)
  .use(router)
  .provide('$axios', axios)  // Provide Axios globally in your app
  .mount('#app');

