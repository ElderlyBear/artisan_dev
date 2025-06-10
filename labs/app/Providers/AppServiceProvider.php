<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\LogObserver;
use App\Models\Role;
use App\Models\Permission;
use Laravel\Fortify\Fortify;

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
        User::observe(LogObserver::class);
        Role::observe(LogObserver::class);
        Permission::observe(LogObserver::class);

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

    }
}
