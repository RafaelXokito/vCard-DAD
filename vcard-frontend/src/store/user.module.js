import UserService from "../services/user.service";

const data = {}
const initialState = {data}

export const user = {
    namespaced: true,
    state: initialState,
    actions: {
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
    },
    mutations: {
        getMeSucess(state, user){
            Object.assign(state.data,user);
        },
        setBalance(state, balance){
            state.data.balance = balance;
        },
    }
};