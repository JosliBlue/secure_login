<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('secure_login.png') }}">
    <title>{{ env('APP_NAME') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/css2-google_fonts.css') }}">
    <script src="{{ asset('js/iconify.min.js') }}"></script>
    <script src="{{ asset('js/tailwind-3_4_16.js') }}"></script>

    <style>
        * {
            font-family: "Inter", sans-serif !important;
            font-optical-sizing: auto;
            font-weight: normal;
            font-style: normal;
        }
    </style>

    @stack('styles')

</head>

<body class="bg-gray-100">

    @if (Route::currentRouteName() != 'show-login' &&
            Route::currentRouteName() != 'verify.otp.form' &&
            Route::currentRouteName() != 'security.question.form')
        <x-header />
    @endif

    @if (Route::currentRouteName() != 'show-login' &&
            Route::currentRouteName() != 'verify.otp.form' &&
            Route::currentRouteName() != 'security.question.form')
        <div class="container mx-auto my-5">
            @yield('content')
        </div>
    @else
        @yield('content')
    @endif

    @stack('scripts')
</body>

</html>
