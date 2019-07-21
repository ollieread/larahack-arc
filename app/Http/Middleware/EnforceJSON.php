<?php

namespace Arc\Http\Middleware;

use Closure;

class EnforceJSON
{
    private $exclude = [];

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
        if (! in_array($request->route()->getName(), $this->exclude, true)) {
            $request->headers->set('Accept', 'application/json');
            $request->headers->set('Content-Type', 'application/json');
        }

        return $next($request);
    }
}
