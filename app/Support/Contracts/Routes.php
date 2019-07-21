<?php

namespace Arc\Support\Contracts;

use Illuminate\Routing\Router;

interface Routes
{
    public function __invoke(Router $router);
}