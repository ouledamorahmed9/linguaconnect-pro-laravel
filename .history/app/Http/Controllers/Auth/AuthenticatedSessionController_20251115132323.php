<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // START OF THE FIX
        // We replace the old, static redirect with our new, smart, role-based logic.
        // This old line is what caused the error:
        // return redirect()->intended(RouteServiceProvider::HOME);

        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }
        
if ($user->hasRole('coordinator')) {
            return redirect()->intended(route('coordinator.dashboard'));
        }
        if ($user->hasRole('teacher')) {
            return redirect()->intended(route('teacher.dashboard'));
        }

        // Default to the client dashboard.
        return redirect()->intended(route('client.dashboard'));
        // END OF THE FIX
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
