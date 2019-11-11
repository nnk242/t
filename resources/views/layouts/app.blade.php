<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    {{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('css')
</head>
<body>
<div id="app">
    <nav class="nav-extended">
        <div class="nav-wrapper">
            <a href="#!" class="brand-logo">{{ config('app.name', 'Gamota') }}</a>
            <a href="#" data-target="mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
            @include('layouts.sidepnav.index')
            <!-- Dropdown Trigger -->
                @guest
                    <li>
                        <a href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li>
                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li><a class="dropdown-trigger" href="#!" data-target="dropdown-user">{{ Auth::user()->name }}<i
                                class="material-icons right">arrow_drop_down</i></a></li>
                @endguest
            </ul>
            <!-- Mobile -->
            <ul class="sidenav" id="mobile">
                @include('layouts.sidepnav.index')
                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <!-- Dropdown Structure -->
        <ul id="dropdown-user" class="dropdown-content">
            @guest
                <li>
                    <a href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li>
                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="divider"></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                </li>
            @endguest
        </ul>
    </nav>
    <main>
        <div class="section no-pad-bot">
            {{--            @include('components.notification.index')--}}
            @yield('content')
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
<script src="{{ asset('js/materialize.min.js') }}" defer></script>
<script src="{{ asset('js/common.js') }}" defer></script>
@yield('js')
@if (\Session::has('error') || \Session::has('success') || \Session::has('warning'))
    <script>
        $(document).ready(function () {
            var toastHTML = '<span>{!!\Session::get('success') . \Session::get('warning') . \Session::get('error') !!}</span><button class="btn-flat toast-action {{ \Session::has('success') ? 'green-text' : (\Session::has('warning') ? 'yellow-text' : 'red-text')}}"><i class="large material-icons">adjust</i></button>';
            M.toast({html: toastHTML})
        })
    </script>
@endif
</body>
</html>
