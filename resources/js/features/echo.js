import Echo from 'laravel-echo';
import Vue  from 'vue';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: 'arc.ollieread.dev',
    wsPort: 6001,
    disableStats: true,
    encrypted: true,
    enabledTransports: [
        'ws',
        'https',
    ],
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
        },
    },
});

Vue.prototype.$echo = window.Echo;