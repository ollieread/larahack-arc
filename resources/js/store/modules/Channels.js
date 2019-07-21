import api     from '../../services/api';
import channel from '../../models/Channel';

export default {
    namespaced: true,

    state: {
        channels: [],
        current: null,
        loaded: false,
    },

    mutations: {
        setChannels(state, channels) {
            state.channels = channels;
        },

        setCurrentChannel(state, channel) {
            state.current   = channel.uuid.toString();
            channel.current = true;
        },

        setLoaded(state, loaded) {
            state.loaded = loaded;
        },

        setChannelOnlineUsers(state, data) {
            data.channel.setOnlineUsers(data.users);
        },
    },

    getters: {
        hasLoaded: state => state.loaded,
        hasChannels: state => state.channels && state.channels.length > 0,
        getChannels: state => state.channels,
        getChannel: state => name => {
            for (let channel of state.channels) {
                if (channel.name === name) {
                    return channel;
                }
            }
        },
        getCurrentChannel: state => {
            let channelIndex = window._.findIndex(state.channels, (channel) => {
                return channel.uuid.is(state.current);
            });

            if (channelIndex > -1) {
                return state.channels[channelIndex];
            }
        },
        isCurrent: state => channel => {
            return state.current && channel.uuid.is(state.current);
        },
    },

    actions: {
        async loadChannels({commit, dispatch}) {
            await api('api:user:channels').send().then(async response => {
                if (response.wasSuccess) {
                    let channels = await Promise.all(response.response.map(async data => {
                        let model = channel(data.id, data.name, data.description);
                        await dispatch('Users/transformUsers', data.users.data, {root: true})
                            .then(users => {
                                model.setUsers(users);
                                window.Echo
                                      .join('channel.' + model.uuid.toString())
                                      .here((onlineUsers) => {
                                          commit('setChannelOnlineUsers', {
                                              channel: model,
                                              users: onlineUsers,
                                          });
                                      })
                                      .joining((user) => {
                                          console.log(user);
                                      })
                                      .leaving((user) => {
                                          console.log(user);
                                      });
                            });
                        return model;
                    }));
                    await commit('setChannels', channels);
                    //await dispatch('loadCurrentChannel');
                    await commit('setLoaded', true);
                }
            });
        },

        async loadCurrentChannel({commit, state}) {
            let currentChannel = null;

            if (state.current) {
                let channelIndex = window._.findIndex(state.channels, (channel) => {
                    return channel.uuid.is(state.current);
                });

                if (channelIndex > -1) {
                    currentChannel = state.channels[channelIndex];
                }
            } else if (state.channels.length > 0) {
                currentChannel = state.channels[0];
            }

            if (currentChannel) {
                await commit('setCurrentChannel', currentChannel);
            }

            return currentChannel;
        },

        async setCurrentChannel({commit, state}, newChannel) {
            if (state.channels && state.channels.length > 0) {
                if (!(newChannel instanceof String)) {
                    await commit('setCurrentChannel', newChannel);
                } else {
                    for (let existingChannel of state.channels) {
                        if (existingChannel.uuid.is(newChannel) || existingChannel.name === newChannel) {
                            await commit('setCurrentChannel', existingChannel);
                            break;
                        }
                    }
                }
            }
        },
    },
};