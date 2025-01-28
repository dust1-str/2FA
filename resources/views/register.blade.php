<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        .valid {
            color: green;
        }
        .invalid {
            color: red;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-xs">
        @if (session('success'))
                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
        @endif
        <form id="registerForm" method="POST" action="{{ route('register') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <h1 class="text-2xl font-bold text-center mb-6">Create account</h1>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input placeholder="Letters only" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" autofocus>
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <div class="flex">
                    <input class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') is-invalid @enderror" id="password" type="password" name="password" maxlength="12">
                @error('password')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                <ul class="text-xs mt-2">
                    <li id="length" class="invalid">At least 8 characters (Max. 12)</li>
                    <li id="uppercase" class="invalid">At least one uppercase letter</li>
                    <li id="lowercase" class="invalid">At least one lowercase letter</li>
                    <li id="number" class="invalid">At least one number</li>
                    <li id="special" class="invalid">At least one special character (@$!%*?&)</li>
                </ul>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                    Confirm Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('confirm_password') is-invalid @enderror" id="confirm_password" type="password" name="confirm_password" maxlength="12">
                @error('confirm_password')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button id="registerButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Register
                </button>
            </div>
            @if ($errors->has('failed'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <span class="block sm:inline">{{ $errors->first('failed') }}</span>
                </div>
            @endif
        </form>
        <p class="text-center text-gray-500 text-xs">
            Already have an account? <a href="{{ route('login.form') }}" class="text-blue-500 hover:text-blue-700">Sign in here</a>
        </p>
    </div>
    <script>
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordInput = document.getElementById('password');
        const lengthRequirement = document.getElementById('length');
        const uppercaseRequirement = document.getElementById('uppercase');
        const lowercaseRequirement = document.getElementById('lowercase');
        const numberRequirement = document.getElementById('number');
        const specialRequirement = document.getElementById('special');
        const successMessage = document.getElementById('success-message');
        const registerButton = document.getElementById('registerButton');
        const registerForm = document.getElementById('registerForm');

        function checkInputs() {
            const passwordValid = passwordInput.value.length >= 8 && /[A-Z]/.test(passwordInput.value) && /[a-z]/.test(passwordInput.value) && /\d/.test(passwordInput.value) && /[!@#$%^&*(),.?":{}|<>]/.test(passwordInput.value);
            if (nameInput.value && emailInput.value && passwordValid && confirmPasswordInput.value) {
                registerButton.disabled = false;
                registerButton.style.cursor = 'pointer';
            } else {
                registerButton.disabled = true;
                registerButton.style.cursor = 'not-allowed';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Deshabilitar el botón inicialmente
            registerButton.disabled = true;
            registerButton.style.cursor = 'not-allowed';
        });

        nameInput.addEventListener('input', checkInputs);
        emailInput.addEventListener('input', checkInputs);
        passwordInput.addEventListener('input', checkInputs);
        confirmPasswordInput.addEventListener('input', checkInputs);
        registerForm.addEventListener('submit', function() {
            registerButton.disabled = true;
            registerButton.style.cursor = 'not-allowed';
        });


        passwordInput.addEventListener('input', function() {
            const value = passwordInput.value;

            // Check length
            if (value.length >= 8) {
                lengthRequirement.classList.remove('invalid');
                lengthRequirement.classList.add('valid');
            } else {
                lengthRequirement.classList.remove('valid');
                lengthRequirement.classList.add('invalid');
            }

            // Check uppercase
            if (/[A-Z]/.test(value)) {
                uppercaseRequirement.classList.remove('invalid');
                uppercaseRequirement.classList.add('valid');
            } else {
                uppercaseRequirement.classList.remove('valid');
                uppercaseRequirement.classList.add('invalid');
            }

            // Check lowercase
            if (/[a-z]/.test(value)) {
                lowercaseRequirement.classList.remove('invalid');
                lowercaseRequirement.classList.add('valid');
            } else {
                lowercaseRequirement.classList.remove('valid');
                lowercaseRequirement.classList.add('invalid');
            }

            // Check number
            if (/[0-9]/.test(value)) {
                numberRequirement.classList.remove('invalid');
                numberRequirement.classList.add('valid');
            } else {
                numberRequirement.classList.remove('valid');
                numberRequirement.classList.add('invalid');
            }

            // Check special character
            if (/[@$!%*?&]/.test(value)) {
                specialRequirement.classList.remove('invalid');
                specialRequirement.classList.add('valid');
            } else {
                specialRequirement.classList.remove('valid');
                specialRequirement.classList.add('invalid');
            }
        });

        // Auto-dismiss success message after 5 seconds
        setTimeout(function() {
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>