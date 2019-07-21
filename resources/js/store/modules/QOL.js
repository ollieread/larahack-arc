export default {
    namespaced: true,

    state: {
        offline: false,
    },

    mutations: {
        setOffline(state) {
            state.offline = true;
        },

        setOnline(state) {
            state.offline = false;
        },
    },

    getters: {
        isOffline: state => state.offline,
    },

    actions: {},
};