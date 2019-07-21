<?php /** @noinspection ThrowRawExceptionInspection */

namespace Arc\Http\Middleware;

use Arc\Services\Auth;
use Closure;
use Illuminate\Validation\UnauthorizedException;

class AuthenticateFromJWT
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
        $token = $request->bearerToken();

        if ($token && $this->auth->auth($token)) {
            return $next($request);
        }

        throw new UnauthorizedException;
    }
}
