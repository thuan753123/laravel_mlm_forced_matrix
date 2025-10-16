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
        $this->registerPolicies();
        
        // Define gates
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
        
        Gate::define('manager', function ($user) {
            return $user->isManager();
        });
        
        Gate::define('agent', function ($user) {
            return $user->isAgent();
        });
        
        Gate::define('member', function ($user) {
            return $user->isMember();
        });
    }
}