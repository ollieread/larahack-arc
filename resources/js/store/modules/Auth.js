import api    from '../../services/api';
import moment from 'moment';
import User   from '../../models/User';

export default {
    namespaced: true,

    state: {
        token: localStorage.getItem('token') || null,
        expiresAt: localStorage.getItem('expiresAt') || null,
        user: null,
    },

    mutations: {
        setAuth(state, response) {
            state.token     = response.token;
            state.expiresAt = response.expiresAt;
            localStorage.setItem('token', response.token);
            localStorage.setItem('expiresAt', response.expiresAt);
            window.Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + state.token;
        },

        resetAuth(state) {
            state.token     = null;
            state.expiresAt = null;

            localStorage.setItem('token', null);
            localStorage.setItem('expiresAt', null);
        },

        setUser(state, user) {
            state.user = new User(user.id, user.username, user.updatedAt, true);
        },
    },

    getters: {
        isAuthed: state => !!state.token,
        getUser: state => state.user,
    },

    actions: {
        async loadUser({commit, state}) {
            if (!state.user) {
                await api('api:user:me').send().then(async response => {
                    if (response.wasSuccess) {
                        await commit('setUser', response.response);
                        await commit('Users/setCurrentUser', state.user, {root: true});
                    }
                });
            }
        },

        async login({commit, state, dispatch}, credentials) {
            return api('api:user:auth')
                .send({
                    login: credentials.username,
                    password: credentials.password,
                })
                .then(response => {
                    if (response.wasSuccess) {
                        let data = response.response;

                        commit('setAuth', {
                            token: data.token,
                            expiresAt: moment().unix(data.expiresAt),
                        });
                    }

                    return response;
                }).then(success => {
                    if (success) {
                        return dispatch('loadUser');
                    }
                });
        },

        async register({commit, state, dispatch}, credentials) {
            return api('api:user:create')
                .send(credentials)
                .then(response => {
                    return !!response.wasSuccess;
                });
        },

        logout({commit}) {
            commit('resetAuth');
        },
    },
};