<?php

namespace Arc\Http\Routes;

use Arc\Http\Actions\API\Channels as Actions;
use Arc\Support\Contracts\Routes;
use Illuminate\Routing\Router;

class ChannelRoutes implements Routes
{

    public function __invoke(Router $router)
    {
        $router->group(['prefix' => '/channels', 'as' => 'channel:', 'middleware' => 'user'], static function (Router $router) {
            $router->get('/')->name('directory')->uses(Actions\Directory::class);
            $router->post('/{uuid}')->name('join')->uses(Actions\Join::class);
        });
    }
}