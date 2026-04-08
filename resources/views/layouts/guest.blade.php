<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Notification meta tags -->
        <meta name="user-id" content="{{ Auth::id() ?? 0 }}">
        <meta name="notification-sound-enabled" content="{{ config('app.env') === 'local' ? 'true' : 'true' }}">
        <meta name="notification-toast-enabled" content="{{ config('app.env') === 'local' ? 'true' : 'true' }}">

        <title>{{ config('app.name', 'MedCura AI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <!-- Global Themes - Light and Dark -->
        <link rel="stylesheet" href="{{ asset('css/global-light-theme.css') }}">
        <link rel="stylesheet" href="{{ asset('css/global-dark-theme.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background: #060d1f !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased" style="background: #060d1f !important;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background: #060d1f !important;">
            <div class="mb-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current" style="color: #00d4aa !important;" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden" style="background: rgba(10, 22, 40, 0.95) !important; border: 1px solid rgba(0, 212, 170, 0.15) !important; border-radius: 16px !important;">
                {{ $slot }}
                
                <!-- Theme Toggle for Guest -->
                <div class="mt-4 text-center">
                    <button data-theme-toggle class="btn btn-sm" type="button" aria-label="Switch to light theme" title="Toggle theme" style="border: 1px solid rgba(0,212,170,0.3); color: #e8ede7; background: rgba(10,20,40,0.6);">
                        <i data-theme-icon class="fas fa-sun" aria-hidden="true"></i> Toggle Theme
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Theme Switcher -->
        <script src="{{ asset('js/theme-switcher.js') }}"></script>
    </body>
</html>
