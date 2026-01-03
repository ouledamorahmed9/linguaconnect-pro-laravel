<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            // Auto-detect based on browser headers
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (in_array($browserLocale, ['ar', 'en'])) {
                App::setLocale($browserLocale);
            } else {
                // Default to Arabic if checking from a region that might prefer it, 
                // but usually default app locale is 'en' or 'ar' in config.
                // We will default to 'ar' as it is the primary content language.
                App::setLocale('ar');
            }
        }

        // Set text direction based on locale
        if (App::getLocale() === 'ar') {
            session(['dir' => 'rtl']);
        } else {
            session(['dir' => 'ltr']);
        }

        return $next($request);
    }
}
