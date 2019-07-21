import Echo from 'laravel-echo';
import Vue  from 'vue';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: process.env.NODE_ENV === 'production' ? 'arc.ollieread.dev' : 'localhost',
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    enabledTransports: [
        'ws',
        'wss',
    ],
    encrypted: false,
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
        },
    },
});

Vue.prototype.$echo = window.Echo;