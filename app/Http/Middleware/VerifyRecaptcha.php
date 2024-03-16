<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerifyRecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
         // Check if the user is attempting to authenticate
        if ($request->is('login') && $request->isMethod('post')) {
            // Check if 2FA is enabled for the user
            if (Auth::user() && Auth::user()->two_factor_secret) {
                // Redirect to 2FA challenge page
                $request->session()->put('2fa_passed', false);
                return redirect()->route('two-factor.login');
            }
        }

        if ($this->shouldVerifyRecaptcha($request)) {
            $recaptcha = $request->input('recaptcha_response');
            if (empty($recaptcha)) {
                // Add error message to the session
                $request->session()->flash('recaptcha_error', 'Please complete the reCAPTCHA validation.');
            } else {
                // Set a flag in the session to indicate that reCAPTCHA was validated
                $request->session()->put('recaptcha_validated', true);
            }
        }

        return $next($request);
    }

    protected function shouldVerifyRecaptcha(Request $request)
    {
        // Implement logic to check if reCAPTCHA should be verified
        $attempts = $request->session()->get('login_attempts', 0);
        return $attempts >= 3;
    }
}
