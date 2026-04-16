{{--
    Shared Navbar — include in all pages
    Pass $transparent = true for homepage (over video hero)
    Default: solid white navbar
--}}
@php
    $isTransparent = $transparent ?? false;
    $megaIcons = [
        'Transports'          => 'bi-truck-front-fill',
        'Nekustamais ipasums' => 'bi-buildings-fill',
        'Elektronikas'        => 'bi-cpu-fill',
        'Majai un darzam'     => 'bi-tree-fill',
        'Apgerbs'             => 'bi-bag-fill',
        'Sports un hobiji'    => 'bi-dribbble',
        'Dzivnieki'           => 'bi-bug-fill',
        'Darbs'               => 'bi-briefcase-fill',
    ];
    $defaultIcon = 'bi-tag-fill';
@endphp

<style>
    .sp-navbar {
        background: {{ $isTransparent ? 'transparent' : 'rgba(255,255,255,0.97)' }};
        border-bottom: 1px solid {{ $isTransparent ? 'rgba(255,255,255,0.1)' : '#e2e8f0' }};
        padding: 0;
        position: {{ $isTransparent ? 'fixed' : 'sticky' }};
        top: 0; left: 0; right: 0;
        z-index: 1030;
        transition: background .35s, border-color .35s, box-shadow .35s;
        {{ $isTransparent ? '' : 'box-shadow: 0 1px 2px rgba(0,0,0,0.05); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);' }}
    }
    .sp-navbar.scrolled {
        background: rgba(255,255,255,0.97);
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        border-bottom-color: #e2e8f0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    /* Brand */
    .sp-navbar .navbar-brand { font-weight: 800; font-size: 1.35rem; display: flex; align-items: center; gap: .5rem; padding: .875rem 0; text-decoration: none; }
    .nav-logo { height: 36px; width: auto; transition: filter .3s; }
    @if($isTransparent)
        .nav-logo { filter: brightness(0) invert(1); }
        .sp-navbar.scrolled .nav-logo { filter: none; }
    @endif

    /* Links */
    .sp-navbar .nav-link {
        color: {{ $isTransparent ? 'rgba(255,255,255,0.9)' : '#334155' }};
        font-weight: 500; font-size: .9rem; padding: .5rem .875rem; border-radius: 8px; transition: all .25s;
    }
    .sp-navbar.scrolled .nav-link, .sp-navbar .nav-link.nav-solid { color: #334155; }
    .sp-navbar .nav-link:hover {
        color: {{ $isTransparent ? '#fff' : '#2563EB' }};
        background: {{ $isTransparent ? 'rgba(255,255,255,0.12)' : '#eff6ff' }};
    }
    .sp-navbar.scrolled .nav-link:hover { color: #2563EB; background: #eff6ff; }
    .sp-navbar .nav-link.active-link { color: #2563EB !important; background: rgba(37,99,235,0.08) !important; font-weight: 600; }
    .sp-navbar.scrolled .nav-link.active-link { color: #2563EB !important; background: rgba(37,99,235,0.08) !important; }

    /* Post Ad button */
    .btn-post-ad {
        background: {{ $isTransparent ? '#fff' : '#2563EB' }};
        color: {{ $isTransparent ? '#2563EB' : '#fff' }} !important;
        font-weight: 600; font-size: .9rem; border: none; padding: .55rem 1.25rem;
        border-radius: 8px; transition: all .25s; display: inline-flex; align-items: center; gap: .4rem; text-decoration: none;
    }
    .sp-navbar.scrolled .btn-post-ad { background: #2563EB; color: #fff !important; }
    .btn-post-ad:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .sp-navbar.scrolled .btn-post-ad:hover { background: #1E40AF; color: #fff !important; }

    /* Register button */
    .sp-nav-register {
        background: {{ $isTransparent ? '#fff' : '#2563EB' }} !important;
        color: {{ $isTransparent ? '#2563EB' : '#fff' }} !important;
        border-radius: 8px !important; font-weight: 600 !important; border: none !important; transition: all .25s !important;
    }
    .sp-navbar.scrolled .sp-nav-register { background: #2563EB !important; color: #fff !important; }

    /* User toggle */
    .sp-user-toggle { display: flex !important; align-items: center; gap: .5rem; color: {{ $isTransparent ? 'rgba(255,255,255,0.9)' : '#334155' }} !important; transition: color .3s !important; }
    .sp-navbar.scrolled .sp-user-toggle { color: #334155 !important; }
    .sp-user-avatar { width: 32px; height: 32px; border-radius: 50%; background: {{ $isTransparent ? 'rgba(255,255,255,0.2)' : '#eff6ff' }}; display: inline-flex; align-items: center; justify-content: center; color: {{ $isTransparent ? '#fff' : '#2563EB' }}; font-size: .9rem; transition: all .3s; }
    .sp-navbar.scrolled .sp-user-avatar { background: #eff6ff; color: #2563EB; }

    /* User menu */
    .sp-user-menu { width: 280px; padding: 0 !important; overflow: hidden; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
    .sp-user-header { display: flex; align-items: center; gap: .75rem; padding: 1rem; }
    .sp-user-avatar-lg { width: 42px; height: 42px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #2563EB; font-size: 1.1rem; flex-shrink: 0; }
    .sp-user-menu .dropdown-item { padding: .6rem 1rem; border-radius: 0; font-size: .88rem; color: #334155; transition: .15s; }
    .sp-user-menu .dropdown-item:hover { background: #eff6ff; color: #2563EB; }
    .sp-user-menu .dropdown-item.text-danger:hover { background: #fef2f2; color: #dc2626; }

    /* Mega Dropdown */
    .sp-mega-dropdown { position: static !important; }
    .sp-mega-menu { width: 100%; border: none !important; border-top: 2px solid #2563EB !important; border-radius: 0 0 12px 12px !important; box-shadow: 0 20px 50px rgba(0,0,0,0.12) !important; padding: 1.5rem 0 !important; margin-top: 0 !important; background: #fff !important; }
    .sp-mega-category { display: flex; align-items: center; gap: .6rem; padding: .5rem .6rem; border-radius: 8px; text-decoration: none; transition: .15s; }
    .sp-mega-category:hover { background: #eff6ff; }
    .sp-mega-icon { width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; display: inline-flex; align-items: center; justify-content: center; color: #2563EB; font-size: 1rem; flex-shrink: 0; transition: .15s; }
    .sp-mega-category:hover .sp-mega-icon { background: #2563EB; color: #fff; }
    .sp-mega-cat-name { font-size: .9rem; font-weight: 600; color: #1e293b; }
    .sp-mega-subs { list-style: none; padding: 0 0 0 3.1rem; margin: .15rem 0 .5rem; }
    .sp-mega-subs li { margin-bottom: .1rem; }
    .sp-mega-subs a { font-size: .82rem; color: #64748b; text-decoration: none; padding: .2rem .5rem; border-radius: 4px; display: inline-block; transition: .15s; }
    .sp-mega-subs a:hover { color: #2563EB; background: #eff6ff; }
    .sp-mega-more { color: #2563EB !important; font-weight: 500; }

    /* Mobile toggler */
    .sp-navbar .navbar-toggler-icon { transition: filter .3s; }
    @if($isTransparent)
        .sp-navbar .navbar-toggler-icon { filter: brightness(0) invert(1); }
        .sp-navbar.scrolled .navbar-toggler-icon { filter: none; }
    @endif

    @media (max-width: 991.98px) {
        .sp-mega-menu { border-top: none !important; box-shadow: none !important; padding: .5rem 0 !important; position: static !important; width: 100% !important; }
        .sp-mega-menu .container { padding: 0; }
        .sp-mega-subs { padding-left: 3.1rem; }
        .sp-user-menu { width: 100%; }
        .btn-post-ad { width: 100%; justify-content: center; margin-top: .5rem; }
        /* Force solid when mobile menu is open */
        .sp-navbar .navbar-collapse.show { background: #fff; padding: .75rem; border-radius: 0 0 12px 12px; }
        .sp-navbar .navbar-collapse.show .nav-link { color: #334155 !important; }
        .sp-navbar .navbar-collapse.show .nav-link:hover { color: #2563EB !important; background: #eff6ff !important; }
        .sp-navbar .navbar-collapse.show .sp-user-toggle { color: #334155 !important; }
        .sp-navbar .navbar-collapse.show .btn-post-ad { background: #2563EB !important; color: #fff !important; }
        .sp-navbar .navbar-collapse.show .sp-nav-register { background: #2563EB !important; color: #fff !important; }
    }
</style>

<nav class="navbar navbar-expand-lg sp-navbar {{ $isTransparent ? '' : 'scrolled' }}" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/home') }}">
            <img src="/img/logo.png" alt="{{ config('app.name') }}" class="nav-logo">
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#spNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="spNavbar">
            <ul class="navbar-nav mx-auto align-items-lg-center gap-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('home') ? 'active-link' : '' }}" href="{{ url('/home') }}">
                        <i class="bi bi-house-door me-1"></i> Sākums
                    </a>
                </li>

                {{-- Categories mega dropdown --}}
                <li class="nav-item dropdown sp-mega-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Kategorijas
                    </a>
                    <div class="dropdown-menu sp-mega-menu">
                        <div class="container">
                            <div class="row">
                                @foreach($menus as $mi => $cat)
                                    <div class="col-lg-3 col-md-4 col-6 mb-2">
                                        <a href="{{ route('browse', ['category' => $cat->id]) }}" class="sp-mega-category">
                                            <i class="bi {{ $megaIcons[$cat->name] ?? $defaultIcon }} sp-mega-icon"></i>
                                            <span class="sp-mega-cat-name">{{ $cat->name }}</span>
                                        </a>
                                        @if($cat->subcategories->count())
                                            <ul class="sp-mega-subs">
                                                @foreach($cat->subcategories->take(4) as $sub)
                                                    <li><a href="{{ route('browse', ['category' => $cat->id]) }}">{{ $sub->name }}</a></li>
                                                @endforeach
                                                @if($cat->subcategories->count() > 4)
                                                    <li><a href="{{ route('browse', ['category' => $cat->id]) }}" class="sp-mega-more">Skatīt visas &rarr;</a></li>
                                                @endif
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('browse*') ? 'active-link' : '' }}" href="{{ route('browse') }}">
                        <i class="bi bi-collection me-1"></i> Sludinājumi
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-lg-center gap-1">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Ielogoties
                            </a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link sp-nav-register" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Reģistrēties
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="btn btn-post-ad" href="{{ url('/ads/create') }}">
                            <i class="bi bi-plus-lg"></i> Ievietot sludinājumu
                        </a>
                    </li>
                    <li class="nav-item dropdown ms-1">
                        <a class="nav-link dropdown-toggle sp-user-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="sp-user-avatar"><i class="bi bi-person-fill"></i></span>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end sp-user-menu">
                            <div class="sp-user-header">
                                <span class="sp-user-avatar-lg"><i class="bi bi-person-fill"></i></span>
                                <div>
                                    <div class="fw-semibold" style="color:#1e293b;">{{ Auth::user()->name }}</div>
                                    <small style="color:#94a3b8;">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                            <hr class="dropdown-divider m-0">
                            <a class="dropdown-item" href="{{ url('/ads/create') }}"><i class="bi bi-plus-circle me-2"></i> Jauns sludinājums</a>
                            <a class="dropdown-item" href="{{ url('/ads') }}"><i class="bi bi-collection me-2"></i> Mani sludinājumi</a>
                            <a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i> Profils</a>
                            <hr class="dropdown-divider m-0">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-left me-2"></i> Iziet</button>
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@if($isTransparent)
<script>
(function() {
    var nav = document.getElementById('mainNavbar');
    if (!nav) return;
    function onScroll() { nav.classList.toggle('scrolled', window.scrollY > 50); }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    var toggler = nav.querySelector('.navbar-toggler');
    if (toggler) toggler.addEventListener('click', function() {
        setTimeout(function() { if (nav.querySelector('.navbar-collapse.show')) nav.classList.add('scrolled'); }, 50);
    });
})();
</script>
@endif
