import Vue         from 'vue';
import router      from '../router';
import store       from '../store';
import VeeValidate from 'vee-validate';

const files = require.context('../components', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.use(VeeValidate);

const app = new Vue({
    el: '#app',
    router,
    store,
});