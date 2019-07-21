<?php

namespace Arc\Providers;

use Arc\Http\Routes;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $webRoutes = [
        Routes\WebRoutes::class,
    ];

    protected $apiRoutes = [
        Routes\UserRoutes::class,
        Routes\ChannelRoutes::class,
    ];

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
             ->middleware('api')
             ->name('api:')
             ->group(function (Router $router) {
                 array_walk($this->apiRoutes, static function (string $routesClass) use ($router) {
                     (new $routesClass)($router);
                 });
             });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
             ->group(function (Router $router) {
                 array_walk($this->webRoutes, static function (string $routesClass) use ($router) {
                     (new $routesClass)($router);
                 });
             });
    }
}
