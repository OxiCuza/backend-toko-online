<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-users', function ($user) {
            // TODO: logic for manage users
            return count(array_intersect(['ADMIN'], json_decode($user->roles)));
        });

        Gate::define('manage-categories', function ($user) {
            // TODO: logic for manage categories
            return count(array_intersect(['ADMIN', 'STAFF'], json_decode($user->roles)));
        });

        Gate::define('manage-books', function ($user) {
            // TODO: logic for manage books
            return count(array_intersect(['ADMIN', 'STAFF'], json_decode($user->roles)));
        });

        Gate::define('manage-orders', function ($user) {
            // TODO: logic for manage orders
            return count(array_intersect(['ADMIN', 'STAFF'], json_decode($user->roles)));
        });
    }
}
