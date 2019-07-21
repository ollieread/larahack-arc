<?php

namespace Arc\Http\Middleware;

use Arc\Services\Auth;
use Closure;

class SetRequestUserResolver
{
    /**
     * @var \Arc\Services\Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->setUserResolver(function () {
            return $this->auth->user();
        });

        return $next($request);
    }
}
