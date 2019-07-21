<template>
    <div class="channel box" v-if="channel">
        <header class="box__header">
            <h1 class="box__header-title">{{ channel.name }}</h1>
        </header>

        <div class="box__body channel__content">

            <main class="chat__body">

            </main>

            <aside class="chat__sidebar">
                <div class="box box--grow box--headerless">
                    <h2 class="box__title">Users</h2>
                    <div class="box__body channel__users">
                        <a href="#" class="box__item channel__user" :class="{'channel__user--current':user.isCurrent, 'channel__user--offline':! channel.isUserOnline(user)}"
                           v-for="(user, index) in channel.users" :key="user.uuid.toString()">
                            {{ user.username }}
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Channel",

        data: () => {
            return {
                channel: null,
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
                    await this.$store.dispatch('Channels/setCurrentChannel', this.channel);
                }
            },
        },
    };
</script>

<style scoped>

</style>