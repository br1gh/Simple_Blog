<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Simple Blog</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{asset('admin/js/jquery.js')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
</head>
<body class="body-bg">
<div id="app">
    @auth
        @if(Auth::user()->isBanned())
            <nav
                class="navbar navbar-expand-md navbar-light progress-bar-striped shadow-sm justify-content-center"
                style="background-color: red">
            <span class="text-white">
                <b>
                    Your account is banned until {{Auth::user()->banned_until}}. You cannot interact with the community.
                </b>
            </span>
            </nav>
        @endif
    @endauth
    <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="background-color: #282a36">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                Simple Blog
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto"></ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->full_name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end bg-warning" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item text-black bg-warning" href="/user/{{Auth::user()->username}}">
                                    Profile
                                </a>

                                @auth
                                    @if(Auth::user()->isAdmin())
                                        <a class="dropdown-item text-black bg-warning"
                                           href="{{ route('admin.users.index') }}">
                                            Admin
                                        </a>
                                    @endif
                                @endauth

                                <a class="dropdown-item text-black bg-warning"
                                   href="{{ route('post', ['post' => \App\Models\Post::find(1)->slug]) }}">
                                    Rules
                                </a>

                                <a class="dropdown-item text-black bg-warning" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="pb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div>
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</div>
<script src="{{asset('js/toastr.min.js')}}"></script>
<script>
    $(document).ready(function () {
        toastr.options = {
            "progressBar": true,
        }
    });
</script>
@stack('js')
@stack('js.end')
</body>
</html>

@if(in_array(request()->route()->uri, ["post/{post}/edit", "/"]))
    <script src="//cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
    <script>
        $(".form-check-input").change(function () {
            $(this).val(this.checked ? 1 : 0)
        });

        CKEDITOR.replace('body', {
            allowedContent: true
        });
    </script>
@endif
