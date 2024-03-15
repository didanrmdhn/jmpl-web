<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="username" :value="__('Username')" />
                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <input type="checkbox" onclick="passwordshow()"> Show Password
                <ul id="password-error" class="text-red-500 list-disc pl-5 px-6"></ul>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <input type="checkbox" onclick="passwordconfshow()"> Show Password
                <div id="confirm-password-error" class="text-red-500"></div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordError = document.getElementById('password-error');

        passwordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);

            let errorMessage = '';

            if (!hasUppercase) {
                errorMessage += '<li>Password must contain at least one uppercase letter.</li>';
            }

            if (password.length < 8) {
                errorMessage += '<li>Password must be 8 digits long.</li>';
            }

            if (!hasNumber) {
                errorMessage += '<li>Password must contain at least one number.</li>';
            }

            passwordError.innerHTML = errorMessage;

            // Ubah warna teks menjadi merah
            if (errorMessage !== '') {
                passwordError.style.color = 'red';
            } else {
                passwordError.style.color = ''; // Reset warna teks jika tidak ada pesan kesalahan
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const confirmPasswordError = document.getElementById('confirm-password-error');

        function validateConfirmPassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword !== password) {
                confirmPasswordError.innerHTML = 'Password confirmation does not match.';
                confirmPasswordError.style.color = 'red';
            } else {
                confirmPasswordError.innerHTML = '';
            }
        }

        confirmPasswordInput.addEventListener('input', validateConfirmPassword);
    });

    function passwordshow() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function passwordconfshow() {
        var x = document.getElementById("password_confirmation");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>