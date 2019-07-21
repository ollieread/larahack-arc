<?php

namespace Arc\Http;

use Arc\Http\Middleware\AuthenticateFromJWT;
use Arc\Http\Middleware\CheckForMaintenanceMode;
use Arc\Http\Middleware\EncryptCookies;
use Arc\Http\Middleware\EnforceJSON;
use Arc\Http\Middleware\SetRequestUserResolver;
use Arc\Http\Middleware\TrimStrings;
use Arc\Http\Middleware\TrustProxies;
use Arc\Http\Middleware\UpdateUserActivity;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            //VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            EnforceJSON::class,
            SetRequestUserResolver::class,
        ],

        'user' => [
            SetRequestUserResolver::class,
            AuthenticateFromJWT::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'bindings'      => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'signed'        => ValidateSignature::class,
        'throttle'      => ThrottleRequests::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        SubstituteBindings::class,
        EnforceJSON::class,
        AuthenticateFromJWT::class,
        SetRequestUserResolver::class,
    ];
}
