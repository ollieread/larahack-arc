<?php

namespace Arc\Http\Routes;

use Arc\Http\Actions\API\Users as Actions;
use Arc\Support\Contracts\Routes;
use Illuminate\Routing\Router;

class UserRoutes implements Routes
{
    public function __invoke(Router $router)
    {
        $router->group(['prefix' => '/user', 'as' => 'user:'], static function (Router $router) {
            $router->post('/auth')->name('auth')->uses(Actions\Login::class);
            $router->post('/register')->name('create')->uses(Actions\Register::class);

            $router->middleware('user')->group(static function (Router $router) {
                $router->get('/me')->name('me')->uses(Actions\Me::class);
                $router->get('/channels')->name('channels')->uses(Actions\Channels::class);
            });
        });
    }
}