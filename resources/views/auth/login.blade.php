<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div>
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required/>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>
            {{-- <div class="mt-4">
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
            </div> --}}
            {{-- @if($recaptcha_required ?? false)
                <div class="mt-4">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>
            @endif --}}
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
            <div class="flex items-center justify-end mt-4">
                <ul>
                    <li>
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                            {{ __('Have not registered an account yet?') }}
                        </a>
                    </li>
                    <li>
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </li>
                </ul>
                <x-button class="ms-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
@push('scripts')
    <script>
        grecaptcha.ready(function () {
            var loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener("submit", function (event) {
                    var recaptchaRequired = {!! json_encode($recaptcha_required ?? false) !!};
                    if (recaptchaRequired) {
                        event.preventDefault();
                        grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', { action: 'login' })
                            .then(function (token) {
                                document.getElementById("g-recaptcha-response").value = token;
                                loginForm.submit();
                            });
                    }
                });
            }
        });
    </script>
@endpush

