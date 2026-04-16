@php use Illuminate\Support\Str; @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sludinājumi — {{ config('app.name', 'SludinajumuPortals') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sp-primary: #2563EB;
            --sp-primary-hover: #1d4ed8;
            --sp-primary-light: #eff6ff;
            --sp-accent: #F59E0B;
            --sp-accent-hover: #d97706;
            --sp-bg: #F8FAFC;
            --sp-white: #ffffff;
            --sp-dark: #0f172a;
            --sp-text: #334155;
            --sp-text-light: #64748b;
            --sp-text-muted: #94a3b8;
            --sp-border: #e2e8f0;
            --sp-success: #10b981;
            --sp-radius: 12px;
            --sp-radius-lg: 16px;
            --sp-shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --sp-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --sp-shadow-lg: 0 10px 25px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--sp-bg);
            color: var(--sp-text);
            -webkit-font-smoothing: antialiased;
        }

        /* ---- Navbar (compact) ---- */
        .sp-navbar {
            background: var(--sp-white);
            border-bottom: 1px solid var(--sp-border);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: var(--sp-shadow-sm);
        }
        .sp-navbar .navbar-brand {
            font-weight: 800; font-size: 1.3rem; color: var(--sp-dark);
            display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 0;
        }
        .sp-navbar .navbar-brand .brand-icon {
            width: 34px; height: 34px; background: var(--sp-primary); border-radius: 9px;
            display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.9rem;
        }
        .sp-navbar .navbar-brand:hover { color: var(--sp-primary); }
        .sp-navbar .nav-link {
            color: var(--sp-text); font-weight: 500; font-size: 0.88rem;
            padding: 0.45rem 0.75rem; border-radius: 8px; transition: all 0.15s;
        }
        .sp-navbar .nav-link:hover { color: var(--sp-primary); background: var(--sp-primary-light); }
        .btn-post-ad {
            background: var(--sp-primary); color: #fff !important; font-weight: 600; font-size: 0.88rem;
            border: none; padding: 0.5rem 1.1rem; border-radius: 8px; display: inline-flex;
            align-items: center; gap: 0.4rem; transition: all 0.15s;
        }
        .btn-post-ad:hover { background: var(--sp-primary-hover); color: #fff !important; }

        /* ---- Hero Banner ---- */
        .browse-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            padding: 2.5rem 0;
            color: #fff;
        }
        .browse-hero h1 {
            font-size: 1.85rem; font-weight: 800; margin-bottom: 0.4rem; letter-spacing: -0.5px;
        }
        .browse-hero p {
            font-size: 0.95rem; opacity: 0.75; margin-bottom: 1.5rem;
        }
        .browse-search {
            background: var(--sp-white); border-radius: var(--sp-radius);
            padding: 5px; display: flex; align-items: stretch;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15); max-width: 680px;
        }
        .browse-search .form-control {
            border: none; font-size: 0.9rem; padding: 0.65rem 1rem; flex: 1; min-width: 0;
        }
        .browse-search .form-control:focus { box-shadow: none; }
        .browse-search .btn-search {
            background: var(--sp-primary); color: #fff; border: none; border-radius: 10px;
            padding: 0 1.4rem; font-weight: 600; font-size: 0.9rem; white-space: nowrap;
            display: flex; align-items: center; gap: 0.4rem; transition: all 0.15s;
        }
        .browse-search .btn-search:hover { background: var(--sp-primary-hover); }
        .browse-result-count {
            font-size: 0.85rem; opacity: 0.7; margin-top: 1rem;
        }

        /* ---- Filters Sidebar ---- */
        .filter-card {
            background: var(--sp-white); border: 1px solid var(--sp-border);
            border-radius: var(--sp-radius); padding: 1.25rem; margin-bottom: 1rem;
        }
        .filter-title {
            font-size: 0.82rem; font-weight: 700; color: var(--sp-dark);
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.85rem;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .filter-card .form-check {
            margin-bottom: 0.4rem;
        }
        .filter-card .form-check-label {
            font-size: 0.85rem; color: var(--sp-text); cursor: pointer;
        }
        .filter-card .form-check-input:checked {
            background-color: var(--sp-primary); border-color: var(--sp-primary);
        }
        .filter-card .cat-count {
            color: var(--sp-text-muted); font-size: 0.78rem; margin-left: auto;
        }
        .filter-card .form-control {
            font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--sp-border);
            padding: 0.5rem 0.75rem;
        }
        .filter-card .form-control:focus {
            border-color: var(--sp-primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .btn-filter {
            background: var(--sp-primary); color: #fff; border: none; border-radius: 8px;
            font-weight: 600; font-size: 0.85rem; padding: 0.55rem 0; width: 100%;
            transition: all 0.15s;
        }
        .btn-filter:hover { background: var(--sp-primary-hover); color: #fff; }
        .btn-clear {
            background: none; border: 1px solid var(--sp-border); color: var(--sp-text-light);
            border-radius: 8px; font-size: 0.82rem; padding: 0.45rem 0; width: 100%;
            transition: all 0.15s; margin-top: 0.5rem;
        }
        .btn-clear:hover { border-color: var(--sp-primary); color: var(--sp-primary); }

        /* ---- Toolbar ---- */
        .browse-toolbar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;
        }
        .browse-toolbar .result-text {
            font-size: 0.9rem; color: var(--sp-text-light);
        }
        .browse-toolbar .result-text strong { color: var(--sp-dark); }
        .browse-toolbar .form-select {
            width: auto; font-size: 0.85rem; border-radius: 8px;
            border: 1px solid var(--sp-border); padding: 0.45rem 2rem 0.45rem 0.75rem;
        }

        /* ---- Ad Cards ---- */
        .sp-ad-card {
            background: var(--sp-white); border: 1px solid var(--sp-border);
            border-radius: var(--sp-radius); overflow: hidden; text-decoration: none;
            display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease;
        }
        .sp-ad-card:hover {
            border-color: transparent; box-shadow: var(--sp-shadow-lg); transform: translateY(-4px);
        }
        .sp-ad-image {
            position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f1f5f9;
        }
        .sp-ad-image img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;
        }
        .sp-ad-card:hover .sp-ad-image img { transform: scale(1.05); }
        .sp-ad-badge {
            position: absolute; top: 10px; left: 10px; padding: 0.25rem 0.6rem;
            border-radius: 6px; font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .sp-ad-badge-new { background: var(--sp-success); color: #fff; }
        .sp-ad-badge-used { background: var(--sp-accent); color: #fff; }
        .sp-ad-body {
            padding: 0.85rem 1rem; flex: 1; display: flex; flex-direction: column;
        }
        .sp-ad-title {
            font-size: 0.88rem; font-weight: 600; color: var(--sp-dark);
            line-height: 1.35; margin-bottom: 0.35rem;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .sp-ad-location {
            font-size: 0.78rem; color: var(--sp-text-muted);
            display: flex; align-items: center; gap: 0.3rem; margin-bottom: 0.5rem;
        }
        .sp-ad-footer {
            display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto;
        }
        .sp-ad-price { font-size: 1.1rem; font-weight: 700; color: var(--sp-primary); }
        .sp-ad-time { font-size: 0.72rem; color: var(--sp-text-muted); }

        /* ---- Pagination ---- */
        .sp-pagination .page-link {
            border: 1px solid var(--sp-border); color: var(--sp-text); font-size: 0.85rem;
            padding: 0.45rem 0.85rem; border-radius: 8px; margin: 0 2px; transition: all 0.15s;
        }
        .sp-pagination .page-link:hover {
            background: var(--sp-primary-light); border-color: var(--sp-primary); color: var(--sp-primary);
        }
        .sp-pagination .page-item.active .page-link {
            background: var(--sp-primary); border-color: var(--sp-primary); color: #fff;
        }

        /* ---- Empty ---- */
        .sp-empty {
            text-align: center; padding: 4rem 1.5rem; background: var(--sp-white);
            border: 2px dashed var(--sp-border); border-radius: var(--sp-radius-lg);
        }

        /* ---- Active filter tags ---- */
        .active-filters { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1rem; }
        .filter-tag {
            display: inline-flex; align-items: center; gap: 0.3rem;
            background: var(--sp-primary-light); color: var(--sp-primary);
            font-size: 0.78rem; font-weight: 600; padding: 0.3rem 0.7rem;
            border-radius: 50px; text-decoration: none; transition: all 0.15s;
        }
        .filter-tag:hover { background: #dbeafe; }

        /* ---- Mobile Filter Toggle ---- */
        .filter-toggle {
            display: none; background: var(--sp-white); border: 1px solid var(--sp-border);
            border-radius: 8px; padding: 0.55rem 1rem; font-weight: 600; font-size: 0.85rem;
            color: var(--sp-text); width: 100%; margin-bottom: 1rem;
            align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer;
        }

        /* ---- Footer ---- */
        .sp-footer {
            background: var(--sp-dark); color: rgba(255,255,255,0.65); padding: 2.5rem 0 1.25rem;
            margin-top: 3rem;
        }
        .sp-footer a { color: rgba(255,255,255,0.55); text-decoration: none; font-size: 0.85rem; }
        .sp-footer a:hover { color: var(--sp-accent); }

        @media (max-width: 991.98px) {
            .filter-toggle { display: flex; }
            .filter-sidebar { display: none; }
            .filter-sidebar.show { display: block; }
            .browse-hero h1 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg sp-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/home') }}">
                <span class="brand-icon"><i class="bi bi-megaphone-fill"></i></span>
                {{ config('app.name', 'SludinajumuPortals') }}
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#browseNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="browseNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}"><i class="bi bi-house-door me-1"></i> Sākums</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('browse') }}"><i class="bi bi-collection me-1"></i> Sludinājumi</a></li>
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Ielogoties</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> Reģistrēties</a></li>
                    @else
                        <li class="nav-item"><a class="btn btn-post-ad" href="{{ url('/ads/create') }}"><i class="bi bi-plus-lg"></i> Ievietot</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero Banner --}}
    <section class="browse-hero">
        <div class="container">
            <h1><i class="bi bi-search me-2"></i>Atrodi savu piedāvājumu</h1>
            <p>Pārlūko {{ $totalResults }} sludinājumus no visas Latvijas</p>
            <form action="{{ route('browse') }}" method="GET" class="browse-search">
                <input type="text" name="search" class="form-control" placeholder="Meklēt sludinājumus..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-search"><i class="bi bi-search"></i> Meklēt</button>
            </form>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="container" style="margin-top: 2rem; margin-bottom: 2rem;">

        {{-- Active Filters --}}
        @if(request('search') || request('category') || request('condition') || request('min_price') || request('max_price'))
            <div class="active-filters">
                <span style="font-size: 0.82rem; color: var(--sp-text-light); margin-right: 0.25rem; padding-top: 0.3rem;">Aktīvie filtri:</span>
                @if(request('search'))
                    <a href="{{ route('browse', request()->except('search')) }}" class="filter-tag">"{{ request('search') }}" <i class="bi bi-x"></i></a>
                @endif
                @if(request('category'))
                    @php $catName = $categories->firstWhere('id', request('category'))?->name; @endphp
                    <a href="{{ route('browse', request()->except('category')) }}" class="filter-tag">{{ $catName }} <i class="bi bi-x"></i></a>
                @endif
                @if(request('condition'))
                    <a href="{{ route('browse', request()->except('condition')) }}" class="filter-tag">{{ request('condition') === 'new' ? 'Jauns' : 'Lietots' }} <i class="bi bi-x"></i></a>
                @endif
                @if(request('min_price') || request('max_price'))
                    <a href="{{ route('browse', request()->except(['min_price','max_price'])) }}" class="filter-tag">&euro;{{ request('min_price', '0') }} — &euro;{{ request('max_price', '∞') }} <i class="bi bi-x"></i></a>
                @endif
                <a href="{{ route('browse') }}" class="filter-tag" style="background: #fef2f2; color: #dc2626;">Notīrīt visus <i class="bi bi-x"></i></a>
            </div>
        @endif

        <div class="row g-4">
            {{-- Sidebar Filters --}}
            <div class="col-lg-3">
                <button class="filter-toggle" onclick="document.getElementById('filterSidebar').classList.toggle('show')">
                    <i class="bi bi-funnel"></i> Rādīt filtrus
                </button>

                <div id="filterSidebar" class="filter-sidebar">
                    <form action="{{ route('browse') }}" method="GET">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Category Filter --}}
                        <div class="filter-card">
                            <div class="filter-title"><i class="bi bi-grid-3x3-gap"></i> Kategorija</div>
                            @foreach($categories as $cat)
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="radio" name="category" value="{{ $cat->id }}" id="cat{{ $cat->id }}"
                                        {{ request('category') == $cat->id ? 'checked' : '' }}>
                                    <label class="form-check-label ms-1 flex-grow-1" for="cat{{ $cat->id }}">{{ $cat->name }}</label>
                                    <span class="cat-count">{{ $cat->ads_count }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Condition Filter --}}
                        <div class="filter-card">
                            <div class="filter-title"><i class="bi bi-check2-circle"></i> Stāvoklis</div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="condition" value="new" id="condNew"
                                    {{ request('condition') === 'new' ? 'checked' : '' }}>
                                <label class="form-check-label" for="condNew">Jauns</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="condition" value="used" id="condUsed"
                                    {{ request('condition') === 'used' ? 'checked' : '' }}>
                                <label class="form-check-label" for="condUsed">Lietots</label>
                            </div>
                        </div>

                        {{-- Price Filter --}}
                        <div class="filter-card">
                            <div class="filter-title"><i class="bi bi-currency-euro"></i> Cena</div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control" placeholder="No" value="{{ request('min_price') }}" min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control" placeholder="Līdz" value="{{ request('max_price') }}" min="0">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-filter"><i class="bi bi-funnel me-1"></i> Filtrēt</button>
                        <a href="{{ route('browse') }}" class="btn btn-clear">Notīrīt filtrus</a>
                    </form>
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="col-lg-9">
                {{-- Toolbar --}}
                <div class="browse-toolbar">
                    <span class="result-text">
                        Atrasti <strong>{{ $totalResults }}</strong> sludinājumi
                    </span>
                    <form action="{{ route('browse') }}" method="GET" class="d-flex align-items-center gap-2">
                        @foreach(request()->except('sort') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Jaunākie</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Vecākie</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Cena: zemākā</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Cena: augstākā</option>
                        </select>
                    </form>
                </div>

                @if($ads->count())
                    <div class="row g-3">
                        @foreach($ads as $ad)
                            <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="sp-ad-card">
                                    <div class="sp-ad-image">
                                        <img src="{{ str_starts_with($ad->feature_image, 'http') ? $ad->feature_image : route('ad.image', basename($ad->feature_image)) }}"
                                             alt="{{ $ad->name }}" loading="lazy">
                                        @if($ad->product_condition)
                                            <span class="sp-ad-badge {{ $ad->product_condition === 'new' ? 'sp-ad-badge-new' : 'sp-ad-badge-used' }}">
                                                {{ $ad->product_condition === 'new' ? 'Jauns' : 'Lietots' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="sp-ad-body">
                                        <div class="sp-ad-title">{{ Str::limit($ad->name, 45) }}</div>
                                        @if($ad->listing_location)
                                            <div class="sp-ad-location">
                                                <i class="bi bi-geo-alt-fill"></i>
                                                {{ Str::limit($ad->listing_location, 30) }}
                                            </div>
                                        @endif
                                        <div class="sp-ad-footer">
                                            <span class="sp-ad-price">&euro;{{ number_format($ad->price, 2) }}</span>
                                            <span class="sp-ad-time">{{ $ad->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($ads->hasPages())
                        <div class="d-flex justify-content-center mt-4 sp-pagination">
                            {{ $ads->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @else
                    <div class="sp-empty">
                        <i class="bi bi-search" style="font-size: 3rem; color: var(--sp-border);"></i>
                        <h5 class="mt-3 fw-semibold" style="color: var(--sp-dark);">Nekas netika atrasts</h5>
                        <p style="color: var(--sp-text-muted); font-size: 0.9rem;">Mēģini mainīt meklēšanas kritērijus vai noņemt filtrus</p>
                        <a href="{{ route('browse') }}" class="btn btn-post-ad mt-2">
                            <i class="bi bi-arrow-counterclockwise"></i> Notīrīt filtrus
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="sp-footer">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span style="font-size: 0.82rem;">&copy; {{ date('Y') }} {{ config('app.name', 'SludinajumuPortals') }}. Visas tiesības aizsargātas.</span>
                <div class="d-flex gap-3">
                    <a href="{{ url('/home') }}">Sākums</a>
                    <a href="{{ route('browse') }}">Sludinājumi</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
