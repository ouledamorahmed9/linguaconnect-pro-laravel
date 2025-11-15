<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // START OF THE FIX
                // Instead of redirecting to a static HOME constant,
                // we check the user's role and redirect to the correct dashboard.
                $user = Auth::user();

                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                }
                
                if ($user->hasRole('teacher')) {
                    return redirect()->route('teacher.dashboard');
                }
                
                // Default to the client dashboard
                return redirect()->route('client.dashboard');
                // END OF THE FIX
            }
        }

        return $next($request);
    }
}
