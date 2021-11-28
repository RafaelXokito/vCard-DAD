import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

import { createApp } from "vue"
import App from "./App.vue"
import router from "./router"
import store from "./store"
import { FontAwesomeIcon } from './plugins/font-awesome'
import axios from 'axios'
import Pagination from 'v-pagination-3'
import Toaster from "@meforma/vue-toaster"

import FieldErrorMessage from "./components/global/FieldErrorMessage.vue"
import ConfirmationDialog from "./components/global/ConfirmationDialog.vue"

let toastOptions = {
    position: 'top',
    timeout: 3000,
    pauseOnHover: true
}

const app = createApp(App)
  .use(router)
  .use(store)
  .use(Toaster, toastOptions)
  .component('pagination', Pagination)
  .component("font-awesome-icon", FontAwesomeIcon)
  .component('field-error-message', FieldErrorMessage)
  .component('confirmation-dialog', ConfirmationDialog)


router.beforeEach((to, from, next) => {
  let auth = store.state.auth;
  if (to.name !== 'login' && to.name !== 'register' 
      && to.name !== 'confirmationPhoneNumber' 
      && !auth.status.loggedIn) next({ name: 'login' })
  else next()
})

axios.defaults.baseURL = "http://localhost/api"
app.config.globalProperties.$axios = axios

app.config.globalProperties.baseURL = "http://localhost/"
app.config.globalProperties.defaultImageProfileURL = "http://localhost/storage/fotos/avatar.jpg"
app.config.globalProperties.logoImageURL = "http://localhost/storage/fotos/logo.png"

app.mount('#app')
