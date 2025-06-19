<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <title>{{ env('APP_NAME') }} - @yield('title')</title>

    {{-- Global CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">

    @stack('css')
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    @yield('content-left')
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    @yield('content-right')
                </div>
            </div>
        </div>
    </div>

    {{-- Global JS --}}
    <script src="{{ asset('assets/static/js/helper/isLoading.js') }}"></script>

    @stack('js')
</body>

</html>
