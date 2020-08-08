<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @guest
    <div class="main-container startpage">
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a class="btn btn-lg btn-primary btn-desktop" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        {{ __('Login') }}
                    </a>
                    <a class="btn btn-lg btn-primary btn-mobile tooltip tooltip-right" href="{{ route('login') }}" data-tooltip="{{ __('Login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                </li>
                <li>
                    <a class="btn btn-lg btn-link btn-desktop" href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i>
                        {{ __('Register') }}
                    </a>
                    <a class="btn btn-lg btn-link btn-mobile tooltip tooltip-right" href="{{ route('register') }}" data-tooltip="{{ __('Register') }}">
                        <i class="fas fa-user-plus"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="language-switcher">
        </div>

        <main>
            @yield('content')
        </main>

        <div class="sidebar-preview">
        </div>
    </div>
    @endguest

    @auth
        <div id="app">
            <App :user="{{ auth()->user() }}"></App>
        </div>
    @endauth

</body>
</html>
