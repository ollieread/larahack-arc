<template>
    <div class="box box--grow box--headerless">
        <h2 class="box__title">Channels</h2>
        <div class="box__body">
            <router-link :to="{name:'channel',params:{'channel':channel.name}}" class="box__item"
                         :class="{'box__item--active':isCurrent(channel)}"
                         v-for="(channel, index) in channels" :key="channel.uuid.toString()">
                <span class="box__item-prefix">{{ String.fromCharCode(97 + index) }}</span>
                {{ channel.name }}
            </router-link>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "ChannelSidebar",

        props: {
            channels: {
                type: Array,
            },
        },

        computed: {
            ...mapGetters({
                isCurrent: 'Channels/isCurrent',
            }),
        },
    };
</script>

<style scoped>

</style>