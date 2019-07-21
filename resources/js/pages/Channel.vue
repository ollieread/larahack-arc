<template>
    <div class="box box--grow box--spaced box--footerless" v-if="channel">
        <header class="box__header">
            <h1 class="box__header-title">{{ channel.name }}</h1>
        </header>

        <div class="box__body channel">
            <main class="chat__body">
                <div class="box box--grow box--footerless box--headerless">
                    <h2 class="box__title">Messages</h2>
                    <div class="box__body box__body--scrollable" ref="messages">
                        <div class="channel__messages">
                            <channel-message v-for="message in channel.messages" :key="message.id"
                                             :message="message"></channel-message>
                        </div>
                    </div>
                </div>

                <div class="box box--footerless box--headerless channel__form">
                    <div class="box__body box__body--flex box__body--bare">
                        <div class="input input__field input__field--grow">
                            <textarea name="message" id="channel-message"
                                      class="input__field-input channel__form-message" placeholder="Your message here"
                                      v-model="message" @keydown="startTyping" @blur="stopTyping"></textarea>
                        </div>
                        <div>
                            <button class="button button--simple" @click.prevent="sendMessage">Send</button>
                            <button class="button button--simple" @click.prevent="resetMessage">Reset</button>
                        </div>
                    </div>
                </div>
            </main>

            <aside class="chat__sidebar">
                <div class="box box--full box--headerless">
                    <h2 class="box__title">Users</h2>
                    <div class="box__body box__body--scrollable">
                        <div class="channel__users">
                            <channel-user v-for="user in channel.users" :key="user.uuid.toString()" :user="user"
                                          :channel="channel"></channel-user>
                        </div>
                    </div>
                </div>
                <div class="box box--headerless channel__details">
                    <h2 class="box__title">Details</h2>
                    <div class="box__body">
                        <div class="input">
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ChannelUser  from '../components/ChannelUser';

    export default {
        name: "Channel",
        components: {ChannelUser},
        data: () => {
            return {
                channel: null,
                messages: [],
                message: '',
                timeout: null,
                typingContinued: false,
            };
        },

        computed: {
            ...mapGetters({
                channels: 'Channels/getChannels',
                getChannel: 'Channels/getChannel',
            }),
        },

        watch: {
            channels() {
                if (!this.channel) {
                    this.loadChannel(this.$route.params.channel);
                }
            },

            messages() {
                this.stayScrolled();
            },
        },

        async created() {
            await this.loadChannel(this.$route.params.channel);
        },

        async beforeRouteUpdate(to, from, next) {
            await this.loadChannel(to.params.channel);
            next();
        },

        methods: {
            async loadChannel(name) {
                this.channel = this.getChannel(name);

                if (this.channel) {
                    await this.$store.commit('Channels/markChannelRead', this.channel);
                    await this.$store.dispatch('Channels/setCurrentChannel', this.channel);
                    this.messages = this.channel.messages;
                }
            },

            stayScrolled() {
                this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
            },

            resetMessage() {
                this.message     = '';
                this.inputHeight = '150px';
                this.stopTyping();
            },

            sendMessage() {
                if (this.message && this.message !== '') {
                    if (this.$store.dispatch('Channels/sendChannelMessage', this.message)) {
                        this.resetMessage();
                    }
                }
            },

            startTyping() {
                if (this.message) {
                    this.$store.dispatch('Channels/startTyping', this.channel);

                    if (this.timeout) {
                        clearTimeout(this.timeout);
                    }

                    this.timeout = setTimeout(() => {
                        this.stopTyping();
                    }, 5000);
                }

                this.stopTyping();
            },

            stopTyping() {
                if (this.timeout) {
                    clearTimeout(this.timeout);
                }

                this.$store.dispatch('Channels/stopTyping', this.channel);
            },
        },
    };
</script>

<style scoped>

</style>