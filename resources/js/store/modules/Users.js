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

    getters: {
        getUser: state => uuid => {
            let index = window._.findIndex(state.users, user => user.uuid.is(uuid));

            if (index > -1) {
                return state.users[index];
            }

            return null;
        },

        getCurrentUser: state => state.currentUser
    },

    actions: {
        async transformUsers({commit, state}, data) {
            let users         = data.users;
            let channel       = data.channel;
            let existingUsers = window._.filter(state.users, existingUser => {
                return window._.findIndex(users, (rawUser) => {
                    return existingUser.uuid.is(rawUser.id);
                }) !== -1;
            });

            existingUsers.forEach(existingUser => {
                let userIndex = window._.findIndex(users, (rawUser) => {
                    return existingUser.uuid.is(rawUser.id);
                });

                if (userIndex > -1) {
                    let rawUser = users[userIndex];
                    existingUser.addPermissions(channel.uuid.toString(), rawUser.permissions);
                }
            });

            let newUsers = window._.filter(users, rawUser => {
                return window._.findIndex(existingUsers, (existingUser) => {
                    return existingUser.uuid.is(rawUser.id);
                }) === -1;
            }).map(rawUser => {
                if (state.currentUser && state.currentUser.uuid.is(rawUser.id)) {
                    state.currentUser.addPermissions(channel.uuid.toString(), rawUser.permissions);
                    return state.currentUser;
                }

                let model = user(rawUser.id, rawUser.username, rawUser.updated_at);
                model.addPermissions(channel.uuid.toString(), rawUser.permissions);

                return model;
            });

            commit('addUsers', newUsers);

            return existingUsers.concat(newUsers);
        },

        async transformUser({commit, state}, data) {
            let rawUser = data.user;
            let channel = data.channel;

            let userIndex = window._.findIndex(state.users, (existingUser) => {
                return existingUser.uuid.is(rawUser.id);
            });

            let model = null;

            if (userIndex > -1) {
                model = state.users[userIndex];
            } else {
                model = user(rawUser.id, rawUser.username, rawUser.updated_at);
                commit('addUsers', [model]);
            }

            model.addPermissions(channel.uuid.toString(), rawUser.permissions);

            return model;
        },
    },
};