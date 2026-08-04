<?php

namespace App\Providers;

use App\Models\Vehicles;
use App\Observers\VehicleObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        // Local testing safety: never email real users from local. Redirect ALL outgoing
        // mail to the developer (cc/bcc are stripped). Only active when APP_ENV=local,
        // so production is unaffected. Override the address via MAIL_LOCAL_REDIRECT in .env.
        if ($this->app->environment('local')) {
            Mail::alwaysTo(env('MAIL_LOCAL_REDIRECT', 'basharat.ali@milele.com'));
        }

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
