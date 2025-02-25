@extends('app')

@section('title', 'Login')

@section('content')
@if (session('message'))
    <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
@endif
<form id="loginForm" method="POST" action="{{ route('login') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @csrf
    <h1 class="text-2xl font-bold text-center mb-6">Sign in</h1>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
        </label>
        <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror"
            id="email" name="email" value="{{ old('email') }}" autofocus>
        @error('email')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Password
        </label>
        <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') is-invalid @enderror"
            id="password" type="password" name="password" maxlength="12">
        @error('password')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex items-center justify-between">
        <button id="loginButton"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="submit">
            Continue
        </button>
    </div>
    @if ($errors->has('failed'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ $errors->first('failed') ?: $errors->first('login') }}</span>
        </div>
    @endif
    @if ($errors->has('verified'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ $errors->first('verified') }}</span>
            <a href="{{ route('verification.resend.form') }}" class="text-blue-500 hover:text-blue-700">Resend verification
                email</a>
        </div>
    @endif
</form>
<p class="text-center text-gray-500 text-xs">
    Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700">Register here</a>
</p>
@endsection
@section('scripts')
<script>
    const resendButton = document.getElementById('resendButton');
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    loginForm.addEventListener('submit', function() {
        loginButton.disabled = true;
        loginButton.style.cursos = 'not allowed';
    });

    document.addEventListener('DOMContentLoaded', function () {
        loginButton.disabled = true;
        loginButton.style.cursor = 'not-allowed';
    });

    function checkInputs() {
        if (email.value && password.value) {
            loginButton.disabled = false;
            loginButton.style.cursor = 'pointer';
        } else {
            loginButton.disabled = true;
            loginButton.style.cursor = 'not-allowed';
        }
    }

    password.addEventListener('input', checkInputs);
    email.addEventListener('input', checkInputs);

    const successMessage = document.getElementById('successMessage');
    // Auto-dismiss success message after 10 seconds
    setTimeout(function () {
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
</script>
@endsection