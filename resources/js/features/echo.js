import Echo from 'laravel-echo';
import Vue  from 'vue';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: process.env.MIX_WS_HOST,
    wsPort: process.env.MIX_WS_PORT,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
        },
    },
});

Vue.prototype.$echo = window.Echo;