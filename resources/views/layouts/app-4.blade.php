<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }} - @yield('title')</title>

    {{-- Tambahkan gaya global --}}
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" />
    <link rel="stylesheet" href="https://mizaru.my.id/fa-pro/css/all.min.css">

    <style>
        body {
            min-height: 100vh;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
    </style>

    @stack('css')
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

    @yield('content')

    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/static/js/helper/isLoading.js') }}"></script>
    @stack('js')
</body>

</html>
