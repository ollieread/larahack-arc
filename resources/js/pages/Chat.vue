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
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Chat",

        data: () => {
            return {
                currentChannels: [],
            };
        },

        async mounted() {
            if (!this.$store.getters['Channels/hasLoaded']) {
                await this.$store.dispatch('Channels/loadChannels');
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