import AuthService from '../services/auth.service';
import UserService from '../services/user.service';

const user = JSON.parse(localStorage.getItem('user'));
const initialState = user
  ? { status: { loggedIn: true }, user}
  : { status: { loggedIn: false }, user: null};


export const auth = {
  namespaced: true,
  state: initialState,
  actions: {
    login({ commit }, user) {
      return AuthService.login(user).then(
        user => {
          commit('loginSuccess', user);
          return Promise.resolve(user);
        },
        error => {
          commit('loginFailure');
          return Promise.reject(error);
        }
      );
    },
    async getMe({ commit }){
      return await UserService.getMe().then(
        result => {
          commit('getMeSucess', result.data.data);
          return Promise.resolve(result.data.data);
        }
      )
    },
    setBalance({ commit }, balance) {
      commit('setBalance', balance);
    },
    logout({ commit }) {
      AuthService.logout();
      commit('logout');
    },
    register({ commit }, form_data) {
      return AuthService.register(form_data).then(
        result => {
          commit('registerSuccess');
          return Promise.resolve(result);
        },
        error => {
          commit('registerFailure');
          return Promise.reject(error);
        }
      );
    },
  },
  mutations: {
    getMeSucess(state, user){
      Object.assign(state.user,user);
    },
    setBalance(state, balance){
      state.user.balance = balance;
    },
    loginSuccess(state, user) {
      state.status.loggedIn = true;
      state.user = user;
    },
    loginFailure(state) {
      state.status.loggedIn = false;
      state.user = null;
      
    },
    logout(state) {
      this.$socket.emit('logged_out', state.user)
      state.status.loggedIn = false;
      state.user = null;
      
    },
    registerSuccess(state) {
      state.status.loggedIn = false;
      state.user = null;
      
    },
    registerFailure(state) {
      state.status.loggedIn = false;
      state.user = null;
    },
  }
};