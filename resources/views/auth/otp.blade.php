<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-xs">
        @if (session('success'))
                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block">{{ session('success') }}</span>
                </div>
        @endif
        <form method="POST" action="{{ route('otp.verify', ['id' => $id]) }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="otp">
                    We have sent an OTP to your email address.
                    Please enter the code below.
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('otp') is-invalid @enderror" id="otp" type="text" name="otp" maxlength="6" autofocus>
                @error('otp')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Verify OTP
                </button>
            </div>
            @if ($errors->has('failed'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <span class="block sm:inline">{{ $errors->first('failed') }}</span>
                </div>
            @endif
        </form>
        <button id="resend-button" onclick="event.preventDefault(); document.getElementById('resend-otp-form').submit();" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
            Resend code
        </button>
        <form id="resend-otp-form" method="POST" action="{{ route('otp.resend', ['id' => $id]) }}" style="display: none;">
            @csrf
        </form>
    </div>
    <script>
        // Dismiss success message
        const dismissButton = document.getElementById('dismiss-button');
        const successMessage = document.getElementById('success-message');
        const resendButton = document.getElementById('resend-button');

        resendButton.addEventListener('click', function() {
            resendButton.disabled = true;
            resendButton.style.cursor = 'not-allowed';
        });

        if (successMessage) {
            resendButton.disabled = true;
            resendButton.style.cursor = 'not-allowed';
            
            let timeLeft = 60; // Tiempo en segundos
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
</body>
</html>