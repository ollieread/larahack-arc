import user from '../../models/User';

export default {
    namespaced: true,

    state: {
        users: [],
        currentUser: null,
    },

    mutations: {
        setUsers(state, users) {
            state.users = users;
        },

        addUsers(state, users) {
            state.users = state.users.concat(users);
        },

        setCurrentUser(state, user) {
            state.currentUser = user;
        },
    },

    getters: {},

    actions: {
        async transformUsers({commit, state}, users) {
            let existingUsers = window._.filter(state.users, existingUser => {
                return window._.findIndex(users, (rawUser) => {
                    return existingUser.uuid.is(rawUser.id);
                }) !== -1;
            });

            let newUsers = window._.filter(users, rawUser => {
                return window._.findIndex(existingUsers, (existingUser) => {
                    return existingUser.uuid.is(rawUser.id);
                }) === -1;
            }).map(rawUser => {
                if (state.currentUser && state.currentUser.uuid.is(rawUser.id)) {
                    return state.currentUser;
                }

                return user(rawUser.id, rawUser.username, rawUser.updated_at);
            });

            commit('addUsers', newUsers);

            return existingUsers.concat(newUsers);
        },
    },
};