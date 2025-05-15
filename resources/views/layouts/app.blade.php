<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-danger shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
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

        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm  navbar-hover">

                <a class="navbar-brand" href="#">
                   
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHover" aria-controls="navbarDD" aria-expanded="false" aria-label="Navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHover">

                    <!-- Left Side Of Navbar -->
                    <ul class="conatainer-fluid navbar-nav">
                        @for($i=0; $i<12;$i++)
                            
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown_remove_drowdown_class_for_clickable_link"
                            arial-haspopup="true"
                            arial-expanded="false">
                            Category
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item dropdown-toggle" href="">Subcategory</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="">Childcategory</a>
                                    </li>


                                </ul>

                              </li>
                            </ul>
                        </li>
                        @endfor
                    </ul>
                </div>
            </nav>
        
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <main class="py-4">
           @yield('content')
        </main>
    </div>
    <style>
        body{
            overflow-x: hidden;
        }
       .navbar-nav{
           height: 55px!important;
       }
        .dropdown:hover>.dropdown-menu {
            display: block;
        }

        @media only screen and(max-width:9991px) {
            .navbar-hover .show>.dropdown-toggle::after {
                transform: rotate(-90deg)
            }
        }

        @media only screen and (min-width:492px) {
            .navbar-hover .collapse ul li {
                position: relative;
            }

            .navbar-hover .collapse ul li:hover>ul {
                display: block
            }

            .navbar-hover .collapse ul ul {
                position: absolute;
                top: 100%;
                left: 0;
                min-width: 100px;
                display: none
            }

            .navbar-hover .collapse ul ul ul {
                position: absolute;
                top: 0;
                left: 100%;
                min-width: 200px;
                display: none
            }
            .navbar-nav > li{
                padding-left:3px!important;
                margin-right:3px!important;
            }
            .vertical-menu a {
            background-color: #fff;
            color: #000;
            display: block;
            padding: 12px;
            text-decoration: none;
        }

        .vertical-menu a:hover {
            background-color: red;
            color: #fff;
        }
        .vertical-menu a.active {
            background-color: red;
            color: #fff;
        }


    }

    </style>
</body>
</html>