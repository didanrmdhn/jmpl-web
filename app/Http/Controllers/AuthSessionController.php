<?php

namespace App\Http\Controllers;

use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthSessionController extends Controller
{
    /**
     * Show the login form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $attempts = $request->session()->get('login_attempts', 0);
        $recaptcha_required = $attempts >= 3;

        return view('auth.login', compact('recaptcha_required'));
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        // Check if reCAPTCHA validation is required and not validated
        if ($this->shouldVerifyRecaptcha($request) && !$request->session()->has('recaptcha_validated')) {
            $validator->addRules(['g-recaptcha-response' => ['required', new Recaptcha()]]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->two_factor_secret){
                $request->session()->put('2fa_passed', false);
            }
            if (Auth::user()->two_factor_secret && $request->session()->get('2fa_passed') == false) {
                $request->session()->put('auth.password_confirmed_at', time());
                return redirect()->route('two-factor.login'); // Redirect to 2FA challenge page
            }
            $request->session()->forget('login_attempts'); // Reset login attempts upon successful login
            $request->session()->forget('recaptcha_validated'); // Clear reCAPTCHA validation flag
            return redirect()->intended(route('dashboard')); // Redirect to dashboard or intended URL after successful login
        }

        // Increment login attempts upon failed login
        $request->session()->put('login_attempts', $request->session()->get('login_attempts', 0) + 1);

        return back()->withErrors(['password' => 'These credentials do not match our records.'])->withInput();
    }

    protected function shouldVerifyRecaptcha(Request $request)
    {
        // Implement logic to check if reCAPTCHA should be verified
        $attempts = $request->session()->get('login_attempts', 0);
        return $attempts >= 3;
    }
}
