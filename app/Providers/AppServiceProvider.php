<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.horizontal', function ($view) {
            $user = Auth::user();
            $assignedRoles = $user ? $user->roles : [];
            $view->with('assignedRoles', $assignedRoles);
        });
    }
}
