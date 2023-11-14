<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
        $this->app['request']->server->set('HTTPS', 'on');
=======
        // $this->app['request']->server->set('HTTPS', 'on');
>>>>>>> 1535753 (e)
    }
}
