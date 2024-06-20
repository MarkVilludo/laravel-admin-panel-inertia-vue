<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- additonal -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        @vite('resources/css/app.css')
    </head>
    <body class="font-sans antialiased">
        @inertia
        <!-- additonal -->
        @vite('resources/assets/js/jquery-3.7.1.min.js')
        @vite('resources/assets/js/bootstrap/bootstrap.bundle.min.js')
        @vite('resources/assets/js/bootstrap/main.js')
    </body>
</html>
