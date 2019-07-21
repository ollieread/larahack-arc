import Vue from 'vue';

var Ziggy = {
    namedRoutes: {
        "api:user:auth": {"uri": "api\/user\/auth", "methods": ["POST"], "domain": null},
        "api:user:create": {"uri": "api\/user\/register", "methods": ["POST"], "domain": null},
        "api:user:me": {"uri": "api\/user\/me", "methods": ["GET", "HEAD"], "domain": null},
        "api:user:channels": {"uri": "api\/user\/channels", "methods": ["GET", "HEAD"], "domain": null},
        "api:channel:directory": {"uri": "api\/channels", "methods": ["GET", "HEAD"], "domain": null},
        "api:channel:join": {"uri": "api\/channels\/{uuid}", "methods": ["POST"], "domain": null}
    },
    baseUrl: 'http://localhost/',
    baseProtocol: 'http',
    baseDomain: 'localhost',
    basePort: false,
    defaultParameters: []
};

if (typeof window.Ziggy !== 'undefined') {
    for (var name in window.Ziggy.namedRoutes) {
        Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
    }
}

Vue.mixin({
    methods: {
        route: route
    }
});

export {
    Ziggy
}
