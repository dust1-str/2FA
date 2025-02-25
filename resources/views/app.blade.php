<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Application')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6LebQMoqAAAAAH7z3A_DuK05wZEW_JyvKZvs2qtg"></script>
    @yield('styles')
</head>

<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-xs">
        @yield('content')
    </div>
    @yield('scripts')
    <script>
        document.addEventListener('submit', function (e) {
            e.preventDefault();
            grecaptcha.ready(function () {
                grecaptcha.execute('6LebQMoqAAAAAH7z3A_DuK05wZEW_JyvKZvs2qtg', { action: 'submit' }).then(function (token) {
                    let form = e.target;
                    let input =document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'g-recaptcha-response';
                    input.value = token;
                    form.appendChild(input);
                    form.submit();
                });
            });
        })
    </script>
</body>

</html>