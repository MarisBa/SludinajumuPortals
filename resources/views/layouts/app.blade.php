<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Izzini Latviju') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-primary shadow-sm border-bottom border-light">
            <div class="container">
                <!-- Website Name on the Left -->
                <a class="navbar-brand fw-bold fs-4 text-white" href="{{ url('/home') }}">
                    Izzini Latviju
                </a>

                <!-- Toggler for mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Right Side -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item me-2">
                                    <a class="btn btn-outline-light fw-bold px-4 py-2" href="{{ route('login') }}">
                                        Pieslēgties
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-light text-primary fw-bold px-4 py-2" href="{{ route('register') }}">
                                        Reģistrēties
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold fs-5 text-white" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 p-2" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item py-2 px-3" href="{{ url('/profile') }}">
                                            <i class="bi bi-person me-2"></i> Profils
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 px-3" href="{{ url('/ads') }}">
                                            <i class="bi bi-stickies me-2"></i> Mani posti
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 px-3 text-danger fw-bold">Iziet</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <main class="py-0">
            @yield('content')
            @include('layouts.footer')
        </main>
    </div>
    <style>

    #productCarousel .card {
        background-color: #007bff; /* Bootstrap primary blue */
        border: 2px solid #0056b3;
        color: white;
    }

    #productCarousel .card-footer,
    #productCarousel .card-body {
        background-color: #007bff;
        color: white;
        border-top: 1px solid #0056b3;
    }

    #productCarousel .card-img-container {
        border-bottom: 2px solid #0056b3;
    }

    #productCarousel .btn {
        background-color: #0056b3;
        border-color: #004080;
        color: white;
    }

    #productCarousel .btn:hover {
        background-color: #003366;
        border-color: #002244;
        color: white;
    }

    /* Optional: Ensure headings inside cards stay white */
    #productCarousel .card-footer p,
    #productCarousel .card-footer strong {
        color: white;
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

    .navbar-brand {
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #ffffff !important;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    position: relative;
    }

    .navbar-brand::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 0;
        width: 0%;
        height: 3px;
        background: #FFF;; /* Accent line color */
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    .navbar-brand:hover {
        transform: scale(1.05);
    }

    .navbar-brand:hover::after {
        width: 100%;
    }

    /* User dropdown */
    .navbar-nav .dropdown-menu {
        border-radius: 0.75rem;
        min-width: 200px;
        padding: 0.5rem 0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        background-color: #ffffff;
    }
    .navbar-nav .dropdown-item {
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }
    .navbar-nav .dropdown-item:hover {
        background-color: #f0f0f0;
    }

    /* Username bigger and bolder */
    .navbar-nav .nav-link.dropdown-toggle {
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 0.5px;
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>