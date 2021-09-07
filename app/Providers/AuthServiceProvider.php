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

        // role
        Gate::define('SuperAdmin', function ($user){
            return $user->role = 'SuperAdmin';
        });

        Gate::define('guest', function ($user){
            return $user->role = 'guest';
        });

        Gate::define('Keuangan', function ($user){
            return count(array_intersect(['SuperAdmin', 'Keuangan']), [$user->role]);
        });

        Gate::define('Kasir', function ($user){
            return count(array_intersect(['SuperAdmin', 'Keuangan', 'Kasir']), [$user->role]);
        });

        Gate::define('Stock', function ($user){
            return count(array_intersect(['SuperAdmin', 'Stock']), [$user->role]);
        });

        Gate::define('CheckStock', function ($user){
            return count(array_intersect(['SuperAdmin', 'Keuangan', 'Kasir', 'Stock']), [$user->role]);
        });
    }
}
