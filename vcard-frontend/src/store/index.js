import { createStore } from "vuex";
import { auth } from "./auth.module";
import { user } from "./user.module";

const store = createStore({
  modules: {
    auth,
    user,
  },
});

export default store;