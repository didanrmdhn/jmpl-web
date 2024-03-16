<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldVerifyRecaptcha($request)) {
            $recaptcha = $request->input('g-recaptcha-response');
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
        // For example, check if the number of failed login attempts exceeds a threshold
        $attempts = $request->session()->get('login_attempts', 0);
        return $attempts >= 3;
    }
}
