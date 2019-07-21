<?php

namespace Arc\Http\Routes;

use Arc\Http\Actions\SPA;
use Arc\Support\Contracts\Routes;
use Illuminate\Routing\Router;

class WebRoutes implements Routes
{

    public function __invoke(Router $router)
    {
        $router->get('{any?}')
               ->where('any', '(.*)')
               ->name('spa')
               ->uses(SPA::class);
    }
}