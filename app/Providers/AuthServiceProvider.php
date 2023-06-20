<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    Auth::viaRequest('custom-token', function ($request) {
        $user = User::find($request->input('userId'));

        // Retrieve the last selected role from the session
        $selectedRole = Session::get('selectedRole');

        // Assign the role from the session if available
        if ($selectedRole) {
            $user->selectedRole = $selectedRole;
        }

        return $user;
    });
    }
}
