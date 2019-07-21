import Vue          from 'vue';
import router       from '../router';
import store        from '../store';
import VeeValidate  from 'vee-validate';
import {mapGetters} from 'vuex';

const files = require.context('../components', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.use(VeeValidate);

const app = new Vue({
    el: '#app',
    router,
    store,

    computed: {
        ...mapGetters({
            authed: 'Auth/isAuthed',
        }),
    },

    watch: {
        authed(value) {
            if (!value && this.$route.meta.auth) {
                this.$router.push({name: 'login'});
            }
        },
    },
});