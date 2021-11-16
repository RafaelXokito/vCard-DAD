import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

import { FontAwesomeIcon } from './plugins/font-awesome'
import axios from 'axios';
import Pagination from 'v-pagination-3';

const app = createApp(App)
  .use(router)
  .use(store)
  .component('pagination', Pagination)
  .component("font-awesome-icon", FontAwesomeIcon);

router.beforeEach((to, from, next) => {
  let user = JSON.parse(localStorage.getItem('user'));
  let auth = store.state.auth;
  if (to.name !== 'login' && !auth.status.loggedIn) next({ name: 'login' })
  else if ( user !== null && (user.user_type !== 'A' && (to.name === 'transactions' ||
                                                          to.name === 'profile' ||
                                                          to.name === 'users') &&
                                                          user.confirmationCode !== true)  ) next({ name: 'confirmationCode' })
  else next()
});

axios.defaults.baseURL = "http://localhost/api"
app.config.globalProperties.$axios = axios

app.config.globalProperties.baseURL = "http://localhost/"
app.config.globalProperties.defaultImageProfileURL = "http://localhost/storage/fotos/default_image.png"
app.config.globalProperties.logoImageURL = "http://localhost/storage/fotos/logo.png"



app.mount('#app');
