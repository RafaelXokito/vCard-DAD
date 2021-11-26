import AuthService from '../services/auth.service';
import UserService from '../services/user.service';
import VCardService from '../services/vcard.service';

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
    logout({ commit }) {
      AuthService.logout();
      commit('logout');
    },
    register({ commit }, user) {
      return AuthService.register(user).then(
        user => {
          commit('registerSuccess',user);
          return Promise.resolve(user);
        },
        error => {
          commit('registerFailure');
          return Promise.reject(error);
        }
      );
    },
    confirmationCode({ commit }, user) {
      return AuthService.confirmationCode(user).then(
        () => {
          commit('confirmationCodeSuccess');
          return Promise.resolve();
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    updateVCardBalance({ commit }, user) {
      return VCardService.getVCard(user["id"],`vcards/${user["id"]}?balance=true`).then(
        user => {
          commit('updateBalance',user);
          return Promise.resolve();
        },
        error => {
          return Promise.reject(error);
        }
      )
    },
  },
  mutations: {
    getMeSucess(state, user){
      Object.assign(state.user,user);
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
      state.status.loggedIn = false;
      state.user = null;
      
    },
    registerSuccess(state, user) {
      state.status.loggedIn = true;
      state.user = user;
      
    },
    registerFailure(state) {
      state.status.loggedIn = false;
      state.user = null;
    },
    confirmationCodeSuccess(state) {
      state.status.loggedIn;
    },
    updateBalance(state, user) {
      state.user = user;
    }
  }
};