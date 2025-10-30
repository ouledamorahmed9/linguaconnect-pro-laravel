<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     * This is now a dynamic method to handle different user roles.
     *
     * @return string
     */
    public function home(): string
    {
        // Check the authenticated user's role and return the correct dashboard URL.
        $user = auth()->user();

        if ($user && $user->hasRole('admin')) {
            return route('admin.dashboard');
        }

        if ($user && $user->hasRole('teacher')) {
            return route('teacher.dashboard');
        }

        // Default to the client dashboard.
        return route('client.dashboard');
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
