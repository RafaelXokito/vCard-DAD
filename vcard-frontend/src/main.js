import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

import { FontAwesomeIcon } from './plugins/font-awesome'
import axios from 'axios';

const app = createApp(App)
  .use(router)
  .use(store)
  .component("font-awesome-icon", FontAwesomeIcon);

axios.defaults.baseURL = "http://localhost/api"
app.config.globalProperties.$axios = axios

app.config.globalProperties.baseURL = "http://localhost/"
app.config.globalProperties.defaultImageProfileURL = "http://localhost/storage/fotos/default_image.png"
app.config.globalProperties.logoImageURL = "http://localhost/storage/fotos/logo.png"



app.mount('#app');
