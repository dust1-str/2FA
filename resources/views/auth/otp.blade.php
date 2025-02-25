@extends('app')

@section('title', 'Verify OTP')

@section('content')
        @if (session('success'))
                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block">{{ session('success') }}</span>
                </div>
        @endif
        <form id="verifyForm" method="POST" action="{{ route('otp.verify', ['id' => $id]) }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="otp">
                    We have sent an OTP to your email address.
                    Please enter the code below.
                </label>
                <input id="otp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('otp') is-invalid @enderror" id="otp" type="text" name="otp" maxlength="6" autofocus>
                @error('otp')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button id="verifyButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Verify OTP
                </button>
            </div>
            @if ($errors->has('failed'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <span class="block sm:inline">{{ $errors->first('failed') }}</span>
                </div>
            @endif
        </form>
        <form id="resend-otp-form" method="POST" action="{{ route('otp.resend', ['id' => $id]) }}" class="span">
            @csrf
            <button id="resend-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Resend code
            </button>
            <a href="{{ route('login') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold mt-70 py-2.5 px-4 rounded focus:outline-none focus:shadow-outline">
            Go back
            </a>
        </form>
@endsection
@section('scripts')
    <script>
        const successMessage = document.getElementById('success-message');
        const resendButton = document.getElementById('resend-button');
        const verifyButton = document.getElementById('verifyButton');
        const verifyForm = document.getElementById('verifyForm');
        const otpInput = document.getElementById('otp');

        document.addEventListener('DOMContentLoaded', function() {
            verifyButton.disabled = true;
            verifyButton.style.cursor = 'not-allowed';
        });

        function checkInputs() {
            if (otpInput.value.length === 6) {
                verifyButton.disabled = false;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        otpInput.addEventListener('input', checkInputs);

        verifyForm.addEventListener('submit', function() {
            verifyButton.disabled = true;
            verifyButton.style.cursor = 'not-allowed';
        });

        resendButton.addEventListener('click', function(e) {
            e.preventDefault();
            resendButton.disabled = true;
            resendButton.style.cursor = 'not-allowed';
            grecaptcha.ready(function () {
                grecaptcha.execute('6LebQMoqAAAAAH7z3A_DuK05wZEW_JyvKZvs2qtg', { action: 'submit' }).then(function (token) {
                    let form = document.getElementById('resend-otp-form');
                    console.log(form);
                    let input =document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'g-recaptcha-response';
                    input.value = token;
                    form.appendChild(input);
                    form.submit();
                });
            });
        });

        if (successMessage) {
            resendButton.disabled = true;
            resendButton.style.cursor = 'not-allowed';
            
            let timeLeft = 60;
            successMessage.style.display = 'block';
            const originalMessage = successMessage.querySelector('span').textContent;
            successMessage.querySelector('span').textContent = `${originalMessage} You can retry in ${timeLeft} seconds.`;
        
            const timer = setInterval(() => {
                timeLeft -= 1;
                successMessage.querySelector('span').textContent = `${originalMessage} You can retry in ${timeLeft} seconds.`;
        
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    successMessage.style.display = 'none';
                    resendButton.disabled = false;
                    resendButton.style.cursor = 'pointer';
                }
            }, 1000);
        }
    </script>
@endsection