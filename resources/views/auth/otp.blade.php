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
                    <button id="dismiss-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline">
                        Entendido
                    </button>
                </div>
        @endif
        <form method="POST" action="{{ route('otp.verify', ['id' => $id]) }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="otp">
                    We have sent an OTP to your email address.
                    Please enter the code below.
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('otp') is-invalid @enderror" id="otp" type="text" name="otp" required autofocus>
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
        <button onclick="event.preventDefault(); document.getElementById('resend-otp-form').submit();" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
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

        if (dismissButton) {
            dismissButton.addEventListener('click', function() {
                successMessage.style.display = 'none';
            });

            // Auto-dismiss success message after 10 seconds
            setTimeout(function() {
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
            }, 10000);
        }
    </script>
</body>
</html>