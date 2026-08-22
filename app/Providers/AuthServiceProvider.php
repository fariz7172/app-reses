<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('superadmin', function ($user) {
            return $user->role === 'Super Admin';
        });

        Gate::define('sudin', function ($user) {
            return $user->role === 'Sudin';
        });

        Gate::define('kecamatan', function ($user) {
            return $user->role === 'Kecamatan';
        });
        
        Gate::define('delete-data', function ($user) {
            return in_array($user->role, ['Super Admin', 'Sudin']);
        });
    }
}
