<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ env('APP_NAME') }} - @yield('title')</title>

    {{-- Global CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}" />
    <link rel="stylesheet" href="https://naramizaru.github.io/awesome-2.0/css/all.css">

    @stack('css')
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        @include('template.sidebar')
        <div id="main">
            @include('template.header')

            <div class="page-heading mt-4">
                <h3>@yield('title')</h3>
            </div>
            <div class="page-content" style="min-height: 100vh">
                @yield('content')
            </div>

            @include('template.footer')
        </div>
    </div>

    {{-- Global JS --}}
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>

    @stack('js')
</body>

</html>
