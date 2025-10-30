<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;

    class EnsureUserHasRole
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
         */
        public function handle(Request $request, Closure $next, string $role): Response
        {
            // If the user is not logged in or does not have the required role,
            // redirect them to the login page.
            if (! $request->user() || ! $request->user()->hasRole($role)) {
                return redirect('login');
            }

            return $next($request);
        }
    }
    
