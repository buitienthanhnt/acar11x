<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://pay.google.com/gp/p/js/pay.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @viteReactRefresh
    @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.tsx"])
    @inertiaHead
</head>

<body class="font-sans antialiased 
    {{-- bg-gradient-to-r from-blue-gray-300 to-blue-gray-400 md:from-blue-gray-500 md:to-blue-gray-700 --}}
    ">
    {{-- default app id is 'app' (<div id='app'></div>) --}}
    @inertia('acar-global')
</body>

</html>
