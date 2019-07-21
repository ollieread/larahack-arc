import Vue      from 'vue';
import Router   from 'vue-router';
import store    from '../store';
import Login    from '../pages/Login';
import Register from '../pages/Register';
import Layout   from '../pages/Layout';
import Chat     from '../pages/Chat';
import Channel  from '../pages/Channel';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Layout,
            children: [
                {
                    path: '/',
                    component: Chat,
                    name: 'chat',
                    meta: {
                        auth: true,
                    },
                    children: [
                        {
                            path: '/channel/:channel',
                            component: Channel,
                            name: 'channel',
                        },
                    ],
                },
                {
                    path: '/login',
                    component: Login,
                    name: 'login',
                    meta: {
                        guest: true,
                    },
                },
                {
                    path: '/register',
                    component: Register,
                    name: 'register',
                    meta: {
                        guest: true,
                    },
                },
            ],
        },

    ],
});

router.beforeEach(async (to, from, next) => {
    let authed = store.getters['Auth/isAuthed'];

    if (authed) {
        await store.dispatch('Auth/loadUser');
    }

    if (to.matched.some(record => record.meta.auth)) {
        if (authed) {
            next();
            return;
        }
        next('/login');
    } else if (to.matched.some(record => record.meta.guest)) {
        if (authed) {
            next('/');
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;