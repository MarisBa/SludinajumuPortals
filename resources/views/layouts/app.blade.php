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
    
    <!-- CONSOLIDATED CSS: Using only Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Optional: Bootstrap Icons for use in the dropdown -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    

    <!-- Scripts (Laravel Vite/Mix compilation) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        
        <!-- TOP NAVBAR (Standard Bootstrap 5) -->
        <nav class="navbar navbar-expand-md navbar-light bg-danger shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
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
                                    <a class="dropdown-item" href="{{ url('/ads/create') }}">
                                        <i class="bi bi-plus-circle me-1"></i> Create Ad
                                    </a>
                                    <a class="dropdown-item" href="{{ url('/ads') }}">
                                        <i class="bi bi-list-task me-1"></i> My Ads
                                    </a>
                                    
                                    <!-- Fixed Logout Form: Using a button styled as a dropdown item -->
                                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
                                        @csrf
                                        <button type="submit" class="btn btn-link nav-link w-full text-start p-2 m-0 border-0 bg-transparent text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                                        </button>
                                    </form>

                                    <!-- Hidden Logout Form (kept for compatibility, though redundant with the visible one) -->
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

        <!-- CATEGORY NAVBAR (Custom Hover/Multi-level) -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm navbar-hover"> 
            <div class="container-fluid px-0"> 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHover" aria-controls="navbarHover" aria-expanded="false" aria-label="Toggle Category Navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHover">
                    <ul class="navbar-nav w-100 d-flex justify-content-between"> 
                        @foreach($menus as $menuItem)
                        <li class="nav-item dropdown flex-grow-1 text-center">
                             <!-- Removed data-toggle="dropdown" to rely purely on hover CSS -->
                            <a class="nav-link dropdown-toggle" href="{{route('category.show', $menuItem->slug)}}">
                                {{ $menuItem->name }}
                            </a>
                            <ul class="dropdown-menu w-100">
                                @foreach($menuItem->subcategories as $subMenuItem)
                                <!-- Added dropdown-submenu class for CSS targeting -->
                                <li class="dropdown-submenu"> 
                                    <a class="dropdown-item dropdown-toggle" href="{{route('subcategory.show', [ $menuItem->slug, $subMenuItem->slug ])}}">
                                        {{$subMenuItem->name}}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($subMenuItem->childcategories as $childMenu)
                                        <li>
                                            <a class="dropdown-item" href="{{route('childcategory.show', [ $menuItem->slug, $subMenuItem->slug, $childMenu->slug ])}}">
                                                {{$childMenu->name}}
                                            </a>
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
        
        <!-- MAIN CONTENT AREA -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <!-- JavaScript Dependencies -->
    
    <!-- CONSOLIDATED JS: Using only Bootstrap 5 JavaScript Bundle (Includes Popper.js). Removed jQuery/BS4. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Style for Navbar-Hover and Multi-Level Dropdown -->
    <style>
        /* CSS for Product Carousel (kept as is) */
        #productCarousel .card { background-color: #007bff; border: 2px solid #0056b3; color: white; }
        #productCarousel .card-footer, #productCarousel .card-body { background-color: #007bff; color: white; border-top: 1px solid #0056b3; }
        #productCarousel .card-img-container { border-bottom: 2px solid #0056b3; }
        #productCarousel .btn { background-color: #0056b3; border-color: #004080; color: white; }
        #productCarousel .btn:hover { background-color: #003366; border-color: #002244; color: white; }
        #productCarousel .card-footer p, #productCarousel .card-footer strong { color: white; }
        
        /* Category Navbar Styling */
        .navbar-hover { padding: 0; }
        /* Use lg breakpoint for full-width nav */
        @media only screen and (min-width: 992px) {
            .navbar-hover .navbar-nav { width: 100%; height: 55px !important; display: flex !important; }
        }
        .navbar-hover .nav-item { flex: 1; text-align: center; position: relative; }
        .navbar-hover .nav-link { padding: 1rem; color: #333; font-weight: 500; }
        .navbar-hover .nav-link:hover { background-color: #f8f9fa; }
        
        /* HOVER FIX: This CSS enables hover instead of click for the main categories */
        .navbar-hover .nav-item.dropdown:hover > .dropdown-menu {
            display: block;
        }

        /* Dropdown Menu Styles */
        .navbar-hover .dropdown-menu {
            width: 100%;
            border-radius: 0;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 0;
            /* Added min-width for mobile/tablet safety */
            min-width: 200px; 
        }

        /* Multi-level Dropdown Styling (Desktop) */
        @media only screen and (min-width: 992px) {
            /* Position the second-level menu */
            .dropdown-submenu {
                position: relative; /* Needed for positioning nested menu */
            }
            .dropdown-submenu .dropdown-menu {
                position: absolute;
                left: 100%;
                top: 0;
                min-width: 200px;
                display: none; /* Initially hidden */
            }

            /* Show second-level menu on hover of the parent li */
            .dropdown-submenu:hover > .dropdown-menu {
                display: block;
            }

            /* Rotate the caret icon for multi-level items */
            .dropdown-submenu .dropdown-toggle::after {
                transform: rotate(-90deg);
                position: absolute;
                right: 1rem;
                top: 1rem;
            }
        }
        
        /* Mobile Responsiveness (Ensures nested menus stack) */
        @media only screen and (max-width: 991px) {
            .navbar-hover .navbar-nav {
                flex-direction: column;
                height: auto !important;
            }
            .navbar-hover .dropdown-menu {
                width: auto;
            }
            /* Nested menus stack on mobile */
            .dropdown-submenu .dropdown-menu {
                position: static;
                padding-left: 1rem;
            }
        }
    </style>
</body>
</html>
