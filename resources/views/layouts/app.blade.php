<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SludinajumuPortals') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --accent: #f59e0b;
            --dark: #1e293b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-600: #475569;
            --gray-800: #1e293b;
        }

        * { font-family: 'Inter', sans-serif; }

        /* Top Navbar */
        .top-navbar {
            background: var(--dark);
            padding: 0.75rem 0;
        }
        .top-navbar .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }
        .top-navbar .navbar-brand:hover { color: var(--accent); }
        .top-navbar .nav-link {
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .top-navbar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        .top-navbar .btn-post-ad {
            background: var(--accent);
            color: var(--dark);
            font-weight: 600;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .top-navbar .btn-post-ad:hover {
            background: #e8900a;
            transform: translateY(-1px);
        }
        .top-navbar .dropdown-menu {
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            padding: 0.5rem;
        }
        .top-navbar .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .top-navbar .dropdown-item:hover { background: var(--gray-100); }

        /* Category Navbar */
        .category-navbar {
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            padding: 0;
        }
        .category-navbar .nav-link {
            color: var(--gray-600);
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.85rem 1.1rem;
            transition: all 0.2s;
            border-bottom: 2px solid transparent;
        }
        .category-navbar .nav-link:hover {
            color: var(--primary);
            background: var(--gray-50);
            border-bottom-color: var(--primary);
        }
        .category-navbar .dropdown-menu {
            border: 1px solid var(--gray-200);
            border-radius: 0 0 0.75rem 0.75rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-top: 0;
            padding: 0.5rem;
            min-width: 220px;
        }
        .category-navbar .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            color: var(--gray-600);
            font-size: 0.88rem;
        }
        .category-navbar .dropdown-item:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        /* Multi-level dropdowns */
        @media (min-width: 992px) {
            .category-navbar .dropdown:hover > .dropdown-menu { display: block; }
            .category-navbar .dropdown-menu .dropdown-toggle::after {
                transform: rotate(-90deg);
                position: absolute;
                right: 1rem;
                top: 50%;
                margin-top: -3px;
            }
            .category-navbar .dropdown-menu li { position: relative; }
            .category-navbar .dropdown-menu ul {
                position: absolute;
                left: 100%;
                top: 0;
                min-width: 200px;
                display: none;
                border: 1px solid var(--gray-200);
                border-radius: 0.75rem;
                box-shadow: 0 10px 40px rgba(0,0,0,0.08);
                background: #fff;
                padding: 0.5rem;
                list-style: none;
            }
            .category-navbar .dropdown-menu li:hover > ul { display: block; }
        }
        @media (max-width: 991px) {
            .category-navbar .navbar-nav { flex-direction: column; }
            .category-navbar .dropdown-menu { width: auto; box-shadow: none; border: none; padding-left: 1rem; }
            .category-navbar .dropdown-menu ul { position: static; padding-left: 1rem; list-style: none; }
        }

        /* Footer */
        .site-footer {
            background: var(--dark);
            color: rgba(255,255,255,0.7);
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }
        .site-footer h6 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        .site-footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.2s;
        }
        .site-footer a:hover { color: var(--accent); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body style="background: var(--gray-50);">
    <div id="app">
        {{-- Top Navbar --}}
        <nav class="navbar navbar-expand-md top-navbar shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <img src="/img/logo.png" alt="{{ config('app.name') }}" style="height:32px;width:auto;filter:brightness(0) invert(1);">
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <i class="bi bi-list text-white fs-4"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav ms-auto align-items-center gap-1">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('Login') }}
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus me-1"></i>{{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="btn btn-post-ad" href="{{ url('/ads/create') }}">
                                    <i class="bi bi-plus-lg me-1"></i> Post Ad
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ url('/ads/create') }}">
                                        <i class="bi bi-plus-circle me-2"></i>Create Ad
                                    </a>
                                    <a class="dropdown-item" href="{{ url('/ads') }}">
                                        <i class="bi bi-collection me-2"></i>My Ads
                                    </a>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i>Profile
                                    </a>
                                    <hr class="dropdown-divider">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-left me-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Category Navbar --}}
        <nav class="navbar navbar-expand-md category-navbar">
            <div class="container">
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCategories">
                    <i class="bi bi-grid-3x3-gap me-1"></i> Categories
                </button>
                <div class="collapse navbar-collapse" id="navbarCategories">
                    <ul class="navbar-nav w-100 justify-content-start gap-0">
                        @foreach($menus as $menuItem)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('category.show', $menuItem->slug) }}" data-toggle="dropdown">
                                {{ $menuItem->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($menuItem->subcategories as $subMenuItem)
                                <li>
                                    <a class="dropdown-item dropdown-toggle" href="{{ route('subcategory.show', [$menuItem->slug, $subMenuItem->slug]) }}">{{ $subMenuItem->name }}</a>
                                    <ul class="dropdown-menu">
                                        @foreach($subMenuItem->childcategories as $childMenu)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('childcategory.show', [$menuItem->slug, $subMenuItem->slug, $childMenu->slug]) }}">{{ $childMenu->name }}</a>
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

        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="site-footer">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <h6><img src="/img/logo.png" alt="{{ config('app.name') }}" style="height:24px;width:auto;filter:brightness(0) invert(1);"></h6>
                        <p class="mb-0" style="font-size: 0.88rem;">Your trusted classifieds portal. Buy and sell with confidence.</p>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h6>Quick Links</h6>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ url('/home') }}">Home</a>
                            <a href="{{ url('/ads') }}">Browse Ads</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h6>Categories</h6>
                        <div class="d-flex flex-column gap-2">
                            @foreach($menus->take(4) as $menu)
                                <a href="{{ route('category.show', $menu->slug) }}">{{ $menu->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h6>Account</h6>
                        <div class="d-flex flex-column gap-2">
                            @guest
                                <a href="{{ route('login') }}">Login</a>
                                <a href="{{ route('register') }}">Register</a>
                            @else
                                <a href="{{ url('/ads/create') }}">Post an Ad</a>
                                <a href="{{ url('/ads') }}">My Ads</a>
                                <a href="{{ route('profile') }}">Profile</a>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="footer-bottom text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'SludinajumuPortals') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
