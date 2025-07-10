<?php

namespace App\Providers;

use App\Models\Vehicles;
use App\Observers\VehicleObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function register()
    {
        $this->app->singleton(GmailService::class, function ($app) {
            return new GmailService();
        });
    }
    public function boot(): void
    {
        // Enforce UTF-8 output encoding
        ini_set('default_charset', 'UTF-8');

        Vehicles::observe(VehicleObserver::class);

        View::composer('partials.horizontal', function ($view) {
            $user = Auth::user();
            $assignedRoles = $user ? $user->roles : [];
            $view->with('assignedRoles', $assignedRoles);
        });
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::useBootstrap();
    }
}
