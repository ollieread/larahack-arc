<template>
    <div>
        <div class="crt--on" v-if="accepted">
            <transition enter-active-class="crt--in" leave-active-class="crt--off">
                <router-view></router-view>
            </transition>

            <div class="modal" :class="{'crt--in':!animating, 'crt--off':animating}" v-if="waiting">
                <div class="box box--small">
                    <header class="box__header">
                        <h2 class="box__header-title">Offline</h2>
                    </header>
                    <div class="box__body text--centered">
                        <p>
                            The server is being updated. Waiting for connectivity to return. The page will refresh once
                            complete.
                        </p>
                        <div class="loader">
                            <div class="loader__body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <photosensitive v-model="accepted"></photosensitive>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Layout",

        data: () => {
            return {
                accepted: false,
                loading: true,
                animating: false,
                waiting: false,
            };
        },

        created() {
            this.$echo
                  .channel('qol')
                  .listen('.status', (data) => {
                      if (data.state) {
                          this.$store.commit('QOL/setOnline');
                      } else {
                          this.$store.commit('QOL/setOffline');
                      }
                  });
        },

        computed: {
            ...mapGetters({
                offlineMode: 'QOL/isOffline',
            }),
        },

        watch: {
            offlineMode(value) {
                if (value) {
                    this.waiting = true;
                } else {
                    if (this.waiting) {
                        setTimeout(() => {
                            this.animating = true;
                            setTimeout(() => {
                                this.waiting = false;
                                window.location.reload();
                            }, 550);
                        }, 2000);
                    }
                }
            },
        },
    };
</script>

<style scoped>

</style>