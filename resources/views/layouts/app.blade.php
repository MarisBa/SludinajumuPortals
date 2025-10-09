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
    
    <!-- Bootstrap Icons for use in the dropdown -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Scripts (Laravel Vite/Mix compilation) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        
        <!-- TOP NAVBAR (Standard Bootstrap 5) -->
        <nav class="navbar navbar-expand-md navbar-dark bg-danger shadow-sm">
            <div class="container">
                <a class="navbar-brand text-white fw-bold" href="{{ url('/home') }}">
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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/forum-posts/create') }}">
                                        <i class="bi bi-plus-circle me-2"></i> Izveidot ierakstu
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/forum-posts') }}">
                                        <i class="bi bi-list-task me-2"></i> Mani ieraksti
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/profile') }}">
                                        <i class="bi bi-list-task me-2"></i> Mans profils
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>

                                    {{-- FIXED LOGOUT FORM: Simplified to use standard dropdown-item look --}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- CATEGORY NAVBAR (Custom Hover/Multi-level) -->
        
        
        <!-- MAIN CONTENT AREA -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <!-- JavaScript Dependencies -->
    
    <!-- CONSOLIDATED JS: Using only Bootstrap 5 JavaScript Bundle (Includes Popper.js). -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // FIX: Prevents multi-level dropdown from closing immediately on mobile/click
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function(element){
                element.addEventListener('click', function(e){
                    
                    if(!this.closest('.navbar-hover').classList.contains('collapsing') && !this.closest('.navbar-hover').classList.contains('show')) {
                        // Desktop/Hover behavior is handled by CSS, but this handles mobile click logic
                        let submenu = this.nextElementSibling;
                        if(submenu && submenu.classList.contains('dropdown-menu')) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Close other open submenus at the same level
                            let parent = this.closest('.dropdown-submenu').parentNode;
                            parent.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(otherMenu){
                                if(otherMenu !== submenu) {
                                    otherMenu.classList.remove('show');
                                }
                            });
                            
                            // Toggle the current submenu
                            submenu.classList.toggle('show');
                        }
                    }
                });
            });
        });
    </script>

    <!-- Custom Style for Navbar-Hover and Multi-Level Dropdown -->
    <style>
        /* Category Navbar Styling */
        .navbar-hover { padding: 0; }
        .navbar-hover .nav-link { padding: 1rem; color: #333; font-weight: 500; }
        .navbar-hover .nav-link:hover { background-color: #f8f9fa; }
        
        /* Desktop: Full-width nav and spacing */
        @media only screen and (min-width: 992px) {
            .navbar-hover .navbar-nav { width: 100%; height: 55px !important; display: flex !important; }
            .navbar-hover .nav-item { flex: 1; text-align: center; position: relative; }

            /* HOVER FIX: Main level */
            .navbar-hover .nav-item.dropdown:hover > .dropdown-menu {
                display: block;
            }

            /* Multi-level Dropdown Styling (Desktop) */
            .dropdown-submenu {
                position: relative; /* Needed for positioning nested menu */
            }
            .dropdown-submenu .dropdown-menu {
                position: absolute;
                left: 100%;
                top: 0;
                min-width: 220px;
                display: none; /* Initially hidden */
                margin-top: -1px; /* Align with parent item */
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
            
            /* Ensure the main category link is still clickable */
            .navbar-hover .nav-item.dropdown > a.nav-link.dropdown-toggle[aria-expanded="false"]::after {
                /* Reset standard caret position */
                transform: none;
                margin-left: .255em;
            }
        }
        
        /* Mobile Responsiveness (Ensures nested menus stack) */
        @media only screen and (max-width: 991px) {
            .navbar-hover .navbar-nav {
                flex-direction: column;
                height: auto !important;
            }
            .navbar-hover .dropdown-menu {
                width: 100%;
            }
            /* Nested menus stack on mobile and are indented */
            .dropdown-submenu .dropdown-menu {
                position: static;
                padding-left: 1.5rem;
                border: none;
                box-shadow: none;
            }
            
            /* Remove the nested menu toggle caret on mobile (optional, but cleaner) */
            .dropdown-submenu .dropdown-toggle::after {
                display: none; 
            }
        }
    </style>
</body>
</html>
