<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use your repository
use App\Repositories;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Repositories\User\UserRepositoryInterfaces::class, Repositories\User\UserRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
