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
        @yield('content')
    </main>
</div>
@guest
@else
    <!-- Get Page -->
    <div class="fixed-action-btn direction-top active" style="bottom: 45px; right: 24px;">
        <a id="menu" class="waves-effect waves-light btn btn-floating cyan btn-large"
           onclick="$('.tap-target').tapTarget('open')"><i
                class="material-icons">menu</i></a>
    </div>

    <!-- Tap Target Structure -->
    <div class="tap-target cyan" data-target="menu">
        <form class="tap-target-content" method="POST" action="{{ route('me.page-selected') }}">
            @csrf
            <div class="chips chips-initial input-field">
                <?php
                $page_selected = App\Components\Page\PageComponent::pageSelected();
                $user_role_pages = App\Components\Page\PageComponent::pageUse();
                ?>
                @isset($page_selected)
                    <div class="chip">
                        <img class="btn-floating" src="{{ $page_selected->page->picture }}">
                        {{ $page_selected->page->name }}
                    </div>
                @endisset
                <input class="input" placeholder="Tìm kiếm..." id="search-input-app">
            </div>

            <div class="center-align display-none" id="preloader-app">
                @include('components.preloader.indeterminate')
            </div>
            <div id="form-app">
                <div class="common-item-page">
                    @foreach($user_role_pages as $item)
                        <div class="chip chip-item-element" title="{{ $item->fb_page_id . $item->page->name }}">
                            <img class="btn-floating"
                                 src="{{ $item->page->picture }}">
                            <label>
                                <input type="radio" name="page_id"
                                       value="{{ $item->fb_page_id }}" {{ isset($page_selected) ? ($page_selected->fb_page_id === $item->fb_page_id ? 'checked' : '') : '' }}/>
                                <span>{{ $item->page->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="no-pad-bot">
                    <button class="btn red waves-effect waves-green">Gửi</button>
                </div>
            </div>
        </form>
    </div>
@endguest
<!-- Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/materialize.min.js') }}" defer></script>
<script src="{{ asset('js/common.js') }}"></script>
@yield('js')
@if (\Session::has('error') || \Session::has('success') || \Session::has('warning'))
    <script>
        $(document).ready(function () {
            var toastHTML = '<span>{!!\Session::get('success') . \Session::get('warning') . \Session::get('error') !!}</span><button class="btn-flat toast-action {{ \Session::has('success') ? 'green-text' : (\Session::has('warning') ? 'yellow-text' : 'red-text')}}"><i class="large material-icons">adjust</i></button>';
            M.toast({html: toastHTML})

            $('.sidenav').sidenav()
            $('select').formSelect()
        })
    </script>
@endif
@guest
@else
    <script>
        $(document).ready(function () {
            $('.tap-target').tapTarget()
        })
        $('#search-input-app').on('keyup', function () {
            $('#preloader-app').addClass('display-block')
            $('#preloader-app').removeClass('display-none')

            $('#form-app').addClass('display-none')
            $('#form-app').removeClass('display-block')
        })

        $('#search-input-app').on('keyup',
            delay(function (e) {
                $('#preloader-app').addClass('display-none')
                $('#preloader-app').removeClass('display-block')

                $('#form-app').addClass('display-block')
                $('#form-app').removeClass('display-none')

                let str_search = stripUnicode($(this).val()).toUpperCase()
                $('.chip-item-element').each(function () {
                    let str = stripUnicode($(this).attr('title')).toUpperCase()
                    if (str.indexOf(str_search) >= 0) {
                        $(this).removeClass('display-none')
                    } else {
                        $(this).addClass('display-none')
                    }
                })
            }, 500)
        )
    </script>
@endguest
</body>
</html>
