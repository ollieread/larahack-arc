<template>
    <div class="chat">
        <div class="crt"></div>
        <aside class="chat__sidebar">
            <div class="box box--footerless">
                <div class="box__body">
            <pre class="logo logo--small">
  :::.    :::::::..     .,-:::::
  ;;`;;   ;;;;``;;;;  ,;;;'````'
 ,[[ '[[,  [[[,/[[['  [[[
c$$$cc$$$c $$$$$$c    $$$
 888   888,888b "88bo,`88bo,__,o,
 YMM   ""` MMMM   "W"   "YUMMMMMP"
            </pre>
                </div>
            </div>
            <channel-sidebar :channels="channels"></channel-sidebar>
        </aside>

        <router-view></router-view>

        <div class="modal" :class="{'crt--in':!animating, 'crt--off':animating}" v-if="loading">
            <div class="box box--small">
                <header class="box__header">
                    <h2 class="box__header-title">Loading</h2>
                </header>
                <div class="box__body text--centered">
                    <div class="loader">
                        <div class="loader__body"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Chat",

        data: () => {
            return {
                currentChannels: [],
                loading: true,
                animating: false,
            };
        },

        async mounted() {
            if (!this.$store.getters['Channels/hasLoaded']) {
                await this.$store.dispatch('Channels/loadChannels');
                this.animating = true;
                setTimeout(() => this.loading = false, 550);
            }
        },

        computed: {
            ...mapGetters({
                channels: 'Channels/getChannels',
                channel: 'Channels/getCurrentChannel',
            }),
        },

        methods: {},
    };
</script>

<style scoped>

</style>