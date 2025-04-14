<?php

namespace App\Providers;

use App\Constants\RoleType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::share('RoleType', RoleType::class);
        View::composer('*', function ($view) {
            $user = Auth::user();
            $role = $user?->role ?? null;
            $view->with('role', $role);
        });
    }
}
