import Echo from 'laravel-echo';
import Vue  from 'vue';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: process.env.NODE_ENV === 'production' ? 'arc.ollieread.dev' : '127.0.0.1',
    wsPort: 6001,
    disableStats: true,
    encrypted: true,
    enabledTransports: ['ws'],
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
        },
    },
});

Vue.prototype.$echo = window.Echo;