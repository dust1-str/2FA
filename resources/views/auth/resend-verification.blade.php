@extends('app')

@section('title', 'Resend email')

@section('content')
        @if ($errors->has('failed'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mb-4" role="alert">
                <span class="block sm:inline">{{ $errors->first('failed') }}</span>
            </div>
        @endif
        <h1 class="text-2xl font-bold text-center mb-6">Resend Verification Email</h1>
        <form id="resendForm" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <div class="mb-4">
                <p class="text-gray-700 text-sm mb-2">Enter your email address to resend the verification email.</p>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" autofocus>
                @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button id="resendButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Send
                </button>
                <a href="{{ route('login') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Go back
                </a>
            </div>
        </form>
@endsection
@section('scripts')
<script>
    const resendButton = document.getElementById('resendButton');
    const resendForm = document.getElementById('resendForm');
    const emailInput = document.getElementById('email');
    const oldEmail = "{{ old('email') }}";

    document.addEventListener('DOMContentLoaded', function() {
        resendButton.disabled = true;
        resendButton.style.cursor = 'not-allowed';
    });

    emailInput.addEventListener('input', function() {
                if (emailInput.value !== oldEmail) {
                    resendButton.disabled = false;
                    resendButton.style.cursor = 'pointer';
                } else {
                    resendButton.disabled = true;
                    resendButton.style.cursor = 'not-allowed';
                }
            });
    
    resendForm.addEventListener('submit', function() {
        resendButton.disabled = true;
        resendButton.style.cursor = 'not-allowed';
    });
</script>
@endsection
