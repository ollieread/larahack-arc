<?php

namespace Arc\Providers;

use Arc\Models\User;
use Arc\Observers\UserObserver;
use Arc\Services\Auth;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(Auth::class, static function (Application $application) {
            return new Auth(
                $application->make(Hasher::class),
                $application->make(PasswordBrokerManager::class)->broker('users')
            );
        }, true);

    }
}
