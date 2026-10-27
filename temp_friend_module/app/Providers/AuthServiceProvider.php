<?php

namespace App\Providers;

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
        // Admin / Super-admin bypass for all permissions
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin') || $user->hasRole('super-admin') || in_array($user->tbl_user_type_idtbl_user_type, [1, 2])) {
                return true;
            }
            return null;
        });
    }
}
