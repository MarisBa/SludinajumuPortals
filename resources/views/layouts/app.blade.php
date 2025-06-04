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

                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm navbar-hover">
            <div class="container-fluid px-0"> <!-- Changed to container-fluid and removed padding -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHover">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHover">
                    <ul class="navbar-nav w-100 d-flex justify-content-between"> <!-- Added w-100 and justify-content-between -->
                        @foreach($menus as $menuItem)
                        <li class="nav-item dropdown flex-grow-1 text-center"> <!-- Added flex-grow-1 and text-center -->
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                {{$menuItem->name}}
                            </a>
                            <ul class="dropdown-menu w-100">
                                @foreach($menuItem->subcategories as $subMenuItem)
                                <li>
                                    <a class="dropdown-item dropdown-toggle" href="#">{{$subMenuItem->name}}</a>
                                    <ul class="dropdown-menu">
                                        @foreach($subMenuItem->childcategories as $childMenu)
                                        <li>
                                            <a class="dropdown-item" href="#">{{$childMenu->name}}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
        
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <main class="py-4">
           @yield('content')
        </main>
    </div>
    <style>
    body {
        overflow-x: hidden;
    }
    
    /* Main navbar styling */
    .navbar-hover {
        padding: 0;
    }
    
    .navbar-hover .navbar-nav {
        width: 100%;
        height: 55px !important;
        display: flex !important;
    }
    
    .navbar-hover .nav-item {
        flex: 1;
        text-align: center;
        position: relative;
    }
    
    .navbar-hover .nav-link {
        padding: 1rem;
        color: #333;
        font-weight: 500;
    }
    
    .navbar-hover .nav-link:hover {
        background-color: #f8f9fa;
    }
    
    /* Dropdown styling */
    .dropdown:hover > .dropdown-menu {
        display: block;
    }
    
    .navbar-hover .dropdown-menu {
        width: 100%;
        border-radius: 0;
        border: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-top: 0;
    }
    
    /* Multi-level dropdown styling */
    @media only screen and (min-width: 992px) {
        .navbar-hover .dropdown-menu .dropdown-toggle::after {
            transform: rotate(-90deg);
            position: absolute;
            right: 1rem;
            top: 1rem;
        }
        
        .navbar-hover .dropdown-menu li {
            position: relative;
        }
        
        .navbar-hover .dropdown-menu ul {
            position: absolute;
            left: 100%;
            top: 0;
            min-width: 200px;
            display: none;
        }
        
        .navbar-hover .dropdown-menu li:hover > ul {
            display: block;
        }
    }
    
    /* Mobile responsiveness */
    @media only screen and (max-width: 991px) {
        .navbar-hover .navbar-nav {
            flex-direction: column;
            height: auto !important;
        }
        
        .navbar-hover .dropdown-menu {
            width: auto;
        }
        
        .navbar-hover .dropdown-menu ul {
            position: static;
            padding-left: 1rem;
        }
    }
</style>
</body>
</html>