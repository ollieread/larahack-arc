<?php

namespace Arc\Http\Routes;

use Arc\Http\Actions\API\Deploy;
use Arc\Support\Contracts\Routes;
use Illuminate\Routing\Router;

class MiscRoutes implements Routes
{

    public function __invoke(Router $router)
    {
        $router->post('/deploy')->name('deploy')->uses(Deploy::class);
    }
}