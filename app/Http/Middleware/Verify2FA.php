<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Verify2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the 2FA flag is set to true in the session
        if ($request->session()->get('2fa_passed', true)) {
            // If 2FA has passed, redirect to the dashboard when accessing the two-factor-challenge route
            if ($request->route()->named('two-factor.login')) {
                return redirect()->route('dashboard');
            }
        } else {
            // If 2FA has not passed, prevent access to the dashboard and redirect to the two-factor-challenge page
            if ($request->route()->named('dashboard')) {
                return redirect()->route('two-factor.login');
            }
        }
        return $next($request);
    }
}
