import api     from '../../services/api';
import channel from '../../models/Channel';
import Message from '../../models/Message';

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

        setChannelOnlineUsers(state, {channel, users}) {
            channel.setOnlineUsers(users);
        },

        addChannelMessage(state, {message, channel}) {
            channel.addMessage(message);

            if (!channel.current) {
                channel.unread = true;
            }
        },

        addOnlineUser(state, {user, channel}) {
            channel.addOnlineUser(user);
        },

        removeOnlineUser(state, {user, channel}) {
            channel.removeOnlineUser(user);
        },

        addUser(state, {user, channel}) {
            channel.addUser(user);
        },

        removeUser(state, {user, channel}) {
            channel.removeUser(user);
        },

        markChannelRead(state, channel) {
            channel.unread = false;
        },

        userStartedTyping(state, {user, channel}) {
            channel.startTyping(user);
        },

        userStoppedTyping(state, {user, channel}) {
            channel.stopTyping(user);
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
        getChannelByUUID: state => uuid => {
            for (let channel of state.channels) {
                if (channel.uuid.is(uuid)) {
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
        async loadChannels({commit, dispatch, getters, rootGetters}) {
            await api('api:user:channels', {
                include: 'users,messages',
            }).send().then(async response => {
                if (response.wasSuccess) {
                    let channels = await Promise.all(response.response.map(async data => {
                        let model = channel(data.id, data.name, data.description);
                        await dispatch('Users/transformUsers', {
                            users: data.users.data,
                            channel: model,
                        }, {root: true})
                            .then(async users => {
                                model.setUsers(users);
                                let events = window.Echo.join('channel.' + model.uuid.toString());

                                events.here(async onlineUsers => {
                                    await dispatch('Users/transformUsers', {
                                        users: onlineUsers,
                                        channel: model,
                                    }, {root: true})
                                        .then(async onlineUsers =>
                                            await commit('setChannelOnlineUsers', {
                                                channel: model,
                                                users: onlineUsers,
                                            }));
                                });

                                events.joining(async rawUser => {
                                    await dispatch('Users/transformUser', {user: rawUser, channel: model}, {root: true})
                                        .then(async user => {
                                            await commit('addOnlineUser', {user: user, channel: model});
                                        });
                                });

                                events.leaving(async rawUser => {
                                    await dispatch('Users/transformUser', {user: rawUser, channel: model}, {root: true})
                                        .then(async user => {
                                            await commit('removeOnlineUser', {user: user, channel: model});
                                        });
                                });

                                events.listen('.user.join', async data => {
                                    await dispatch('Users/transformUser', {
                                        user: data.user,
                                        channel: model,
                                    }, {root: true})
                                        .then(async user => {
                                            await commit('addUser', {user: user, channel: model});
                                        });
                                });

                                events.listen('.message.received', async data => {
                                    await dispatch('addChannelMessage', {message: data.message, channel: model});
                                });

                                events.listen('.user.leave', async data => {
                                    await dispatch('Users/transformUser', {
                                        user: data.user,
                                        channel: model,
                                    }, {root: true})
                                        .then(async user => {
                                            await commit('removeUser', {user: user, channel: model});
                                        });
                                });

                                events.listenForWhisper('typing.start', async (data) => {
                                    let channel = getters.getChannelByUUID(data.channel);
                                    let user    = rootGetters['Users/getUser'](data.user);
                                    await commit('userStartedTyping', {user, channel});
                                });

                                events.listenForWhisper('typing.stop', async (data) => {
                                    let channel = getters.getChannelByUUID(data.channel);
                                    let user    = rootGetters['Users/getUser'](data.user);
                                    await commit('userStoppedTyping', {user, channel});
                                });
                            });

                        model.messages = await Promise.all(data.messages.data.map(async data => {
                            let user = rootGetters['Users/getUser'](data.user);
                            return new Message(data.id, data.type, data.message, data.created_at, user, data.action, data.metadata, data.messages);
                        }));

                        return model;
                    }));
                    await commit('setChannels', channels);
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

        async sendChannelMessage({commit, dispatch, state, getters}, message) {
            let channel = getters.getCurrentChannel;
            return await api('api:channel:post', channel.uuid.toString())
                .send({message})
                .then(async response => response.wasSuccess);
        },

        async addChannelMessage({commit, dispatch, state, getters, rootGetters}, {channel, message}) {
            let user  = rootGetters['Users/getUser'](message.user);
            let model = new Message(message.id, message.type, message.message, message.created_at, user, message.action, message.metadata, message.mentions);
            await commit('addChannelMessage', {channel, message: model});
        },

        async startTyping({commit, state, rootGetters}, channel) {
            let user = rootGetters['Users/getCurrentUser'];
            window.Echo.join('channel.' + channel.uuid.toString()).whisper('typing.start', {
                user: user.uuid.toString(),
                channel: channel.uuid.toString(),
            });
            await commit('userStartedTyping', {user, channel});
        },

        async stopTyping({commit, state, rootGetters}, channel) {
            let user = rootGetters['Users/getCurrentUser'];
            window.Echo.join('channel.' + channel.uuid.toString()).whisper('typing.stop', {
                user: user.uuid.toString(),
                channel: channel.uuid.toString(),
            });
            await commit('userStoppedTyping', {user, channel});
        },
    },
};