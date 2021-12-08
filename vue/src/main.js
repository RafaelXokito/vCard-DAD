import "primevue/resources/themes/saga-blue/theme.css"; //theme
import "primevue/resources/primevue.min.css"; //core css
import "primeicons/primeicons.css"; //icons

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { FontAwesomeIcon } from "./plugins/font-awesome";
import axios from "axios";
import Toaster from "@meforma/vue-toaster";
import ConfirmationService from "primevue/confirmationservice";
import PrimeVue from "primevue/config";
import VueSocketIO from "vue-3-socket.io";


import FieldErrorMessage from "./components/global/FieldErrorMessage.vue";
import ConfirmationDialog from "./components/global/ConfirmationDialog.vue";

let toastOptions = {
  position: "top",
  timeout: 3000,
  pauseOnHover: true,
};

const apiDomain = process.env.VUE_APP_API_DOMAIN
const wsConnection = process.env.VUE_APP_WS_CONNECTION

const socketIO = new VueSocketIO({
  debug: true,
  connection: wsConnection,
  vuex: {
    store,
    actionPrefix: "SOCKET_",
    mutationPrefix: "SOCKET_",
  },
});


const app = createApp(App)
  .use(router)
  .use(store)
  .use(PrimeVue)
  .use(ConfirmationService)
  .use(Toaster, toastOptions)
  .use(socketIO)
  .component("font-awesome-icon", FontAwesomeIcon)
  .component("field-error-message", FieldErrorMessage)
  .component("confirmation-dialog", ConfirmationDialog);

store.$socket = socketIO.io;

router.beforeEach((to, from, next) => {
  let auth = store.state.auth;
  if (
    to.name !== "login" &&
    to.name !== "register" &&
    to.name !== "confirmationPhoneNumber" &&
    !auth.status.loggedIn
  )
    next({ name: "login" });
  else next();
});

axios.defaults.baseURL = `${apiDomain}/api`;
app.config.globalProperties.$axios = axios;

app.config.globalProperties.baseURL = apiDomain;
app.config.globalProperties.defaultImageProfileURL =
  `${apiDomain}/storage/fotos/avatar.jpg`;
app.config.globalProperties.logoImageURL =
  `${apiDomain}/storage/fotos/logo.png`;

app.mount("#app");
