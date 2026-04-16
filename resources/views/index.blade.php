@php use Illuminate\Support\Str; @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SludinajumuPortals') }} — Pirkt un pārdot Latvijā</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ========================================
           CSS VARIABLES
           ======================================== */
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
            --sp-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
            --sp-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --sp-shadow-lg: 0 10px 25px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
            --sp-shadow-xl: 0 20px 50px -12px rgba(0,0,0,0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--sp-bg);
            color: var(--sp-text);
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar styles handled by partials/navbar.blade.php */


        /* ========================================
           2. HERO (Video Background)
           ======================================== */
        .sp-hero {
            position: relative;
            padding: 9rem 0 5rem;
            overflow: hidden;
            background: var(--sp-dark);
            min-height: 600px;
        }

        .sp-hero-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            transform: translate(-50%, -50%);
            object-fit: cover;
            z-index: 0;
        }

        .sp-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(15, 23, 42, 0.55) 0%,
                rgba(15, 23, 42, 0.40) 30%,
                rgba(15, 23, 42, 0.50) 60%,
                rgba(15, 23, 42, 0.75) 100%
            );
            z-index: 1;
        }

        .sp-hero .container {
            position: relative;
            z-index: 2;
        }

        .sp-hero-title {
            font-size: 3.25rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -1.5px;
            line-height: 1.1;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 30px rgba(0,0,0,0.2);
        }

        .sp-hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2rem;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Search Bar */
        .sp-search-bar {
            max-width: 720px;
            margin: 0 auto 1.5rem;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: var(--sp-radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            padding: 6px;
            display: flex;
            align-items: stretch;
        }

        .sp-search-bar .form-control,
        .sp-search-bar .form-select {
            border: none;
            font-size: 0.92rem;
            color: var(--sp-text);
            padding: 0.75rem 1rem;
            background: transparent;
        }

        .sp-search-bar .form-control:focus,
        .sp-search-bar .form-select:focus {
            box-shadow: none;
            outline: none;
        }

        .sp-search-bar .form-control {
            flex: 1;
            min-width: 0;
        }

        .sp-search-bar .form-select {
            width: auto;
            max-width: 180px;
            border-left: 1px solid var(--sp-border);
            border-radius: 0;
            cursor: pointer;
            color: var(--sp-text-light);
        }

        .sp-search-bar .btn-search {
            background: var(--sp-primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0 1.75rem;
            font-weight: 600;
            font-size: 0.92rem;
            white-space: nowrap;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sp-search-bar .btn-search:hover {
            background: var(--sp-primary-hover);
        }

        /* Stats */
        .sp-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
        }

        .sp-stat {
            text-align: center;
        }

        .sp-stat-number {
            display: block;
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
        }

        .sp-stat-label {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.55);
            font-weight: 500;
        }

        /* ========================================
           3. CATEGORIES
           ======================================== */
        .sp-section {
            padding: 3.5rem 0;
        }

        .sp-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
        }

        .sp-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--sp-dark);
            margin: 0;
        }

        .sp-section-link {
            color: var(--sp-primary);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            transition: gap 0.2s ease;
        }

        .sp-section-link:hover {
            gap: 0.6rem;
            color: var(--sp-primary-hover);
        }

        .sp-category-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.5rem 1rem;
            background: var(--sp-white);
            border: 1px solid var(--sp-border);
            border-radius: var(--sp-radius);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sp-category-card:hover {
            border-color: var(--sp-primary);
            box-shadow: var(--sp-shadow-md);
            transform: translateY(-2px);
        }

        .sp-category-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: var(--sp-primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .sp-category-card:hover .sp-category-icon {
            background: var(--sp-primary);
        }

        .sp-category-icon i {
            font-size: 1.4rem;
            color: var(--sp-primary);
            transition: color 0.2s ease;
        }

        .sp-category-card:hover .sp-category-icon i {
            color: #fff;
        }

        .sp-category-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--sp-dark);
            margin-bottom: 0.2rem;
        }

        .sp-category-count {
            font-size: 0.78rem;
            color: var(--sp-text-muted);
        }

        /* ========================================
           4. AD CARDS
           ======================================== */
        .sp-ad-card {
            background: var(--sp-white);
            border: 1px solid var(--sp-border);
            border-radius: var(--sp-radius);
            overflow: hidden;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.25s ease;
        }

        .sp-ad-card:hover {
            border-color: transparent;
            box-shadow: var(--sp-shadow-lg);
            transform: translateY(-4px);
        }

        .sp-ad-image {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: #f1f5f9;
        }

        .sp-ad-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .sp-ad-card:hover .sp-ad-image img {
            transform: scale(1.05);
        }

        .sp-ad-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .sp-ad-badge-new {
            background: var(--sp-success);
            color: #fff;
        }

        .sp-ad-badge-used {
            background: var(--sp-accent);
            color: #fff;
        }

        .sp-ad-body {
            padding: 0.875rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .sp-ad-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--sp-dark);
            line-height: 1.35;
            margin-bottom: 0.4rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sp-ad-location {
            font-size: 0.78rem;
            color: var(--sp-text-muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin-bottom: 0.6rem;
        }

        .sp-ad-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
        }

        .sp-ad-price {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--sp-primary);
        }

        .sp-ad-time {
            font-size: 0.72rem;
            color: var(--sp-text-muted);
        }

        /* ========================================
           5. HOW IT WORKS
           ======================================== */
        .sp-how-section {
            background: linear-gradient(180deg, var(--sp-white) 0%, #f1f5f9 100%);
            padding: 5rem 0;
            position: relative;
        }

        .sp-how-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--sp-primary-light);
            color: var(--sp-primary);
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.75rem;
        }

        .sp-how-heading {
            font-size: 2rem;
            font-weight: 800;
            color: var(--sp-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .sp-how-subheading {
            font-size: 1rem;
            color: var(--sp-text-light);
            max-width: 460px;
            margin: 0 auto 3rem;
        }

        .sp-steps-row {
            position: relative;
        }

        /* Connector line between steps (desktop) */
        .sp-steps-row::before {
            content: '';
            position: absolute;
            top: 52px;
            left: 20%;
            right: 20%;
            height: 2px;
            background: repeating-linear-gradient(
                90deg,
                var(--sp-border) 0px,
                var(--sp-border) 8px,
                transparent 8px,
                transparent 16px
            );
            z-index: 0;
        }

        .sp-step-card {
            background: var(--sp-white);
            border: 1px solid var(--sp-border);
            border-radius: var(--sp-radius-lg);
            padding: 2rem 1.5rem 1.75rem;
            text-align: center;
            position: relative;
            z-index: 1;
            transition: all 0.25s ease;
            height: 100%;
        }

        .sp-step-card:hover {
            border-color: var(--sp-primary);
            box-shadow: var(--sp-shadow-lg);
            transform: translateY(-6px);
        }

        .sp-step-icon-wrap {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            position: relative;
            transition: all 0.25s ease;
        }

        .sp-step-card:hover .sp-step-icon-wrap {
            transform: scale(1.08);
        }

        .sp-step-icon-wrap i {
            font-size: 1.8rem;
        }

        .sp-step-number {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--sp-dark);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--sp-white);
        }

        .sp-step-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--sp-dark);
            margin-bottom: 0.5rem;
        }

        .sp-step-desc {
            font-size: 0.88rem;
            color: var(--sp-text-light);
            line-height: 1.6;
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .sp-steps-row::before {
                display: none;
            }
            .sp-how-heading {
                font-size: 1.5rem;
            }
        }

        /* ========================================
           6. CTA BANNER
           ======================================== */
        .sp-cta {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);
            border-radius: var(--sp-radius-lg);
            padding: 3.5rem 2rem;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .sp-cta::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -60px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        .sp-cta::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .sp-cta-content {
            position: relative;
            z-index: 2;
        }

        .sp-cta h2 {
            font-size: 1.85rem;
            font-weight: 800;
            margin-bottom: 0.6rem;
        }

        .sp-cta p {
            font-size: 1.05rem;
            opacity: 0.85;
            margin-bottom: 1.75rem;
        }

        .btn-cta {
            background: var(--sp-accent);
            color: var(--sp-dark);
            font-weight: 700;
            font-size: 1rem;
            border: none;
            padding: 0.85rem 2.25rem;
            border-radius: var(--sp-radius);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-cta:hover {
            background: var(--sp-accent-hover);
            color: var(--sp-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245,158,11,0.35);
        }

        /* ========================================
           7. FOOTER
           ======================================== */
        .sp-footer {
            background: var(--sp-dark);
            color: rgba(255,255,255,0.65);
            padding: 3.5rem 0 1.5rem;
        }

        .sp-footer-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 0.75rem;
        }

        .sp-footer-brand .brand-icon-sm {
            width: 32px;
            height: 32px;
            background: var(--sp-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
        }

        .sp-footer h6 {
            color: #fff;
            font-weight: 600;
            font-size: 0.92rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sp-footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sp-footer-links li {
            margin-bottom: 0.6rem;
        }

        .sp-footer-links a {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.88rem;
            transition: all 0.15s ease;
        }

        .sp-footer-links a:hover {
            color: var(--sp-accent);
            padding-left: 4px;
        }

        .sp-footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 1.5rem;
            margin-top: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
        }

        .sp-footer-social a {
            color: rgba(255,255,255,0.4);
            font-size: 1.1rem;
            margin-left: 1rem;
            transition: color 0.15s ease;
        }

        .sp-footer-social a:hover {
            color: var(--sp-accent);
        }

        /* ========================================
           EMPTY STATE
           ======================================== */
        .sp-empty {
            text-align: center;
            padding: 3.5rem 1rem;
            background: var(--sp-white);
            border: 2px dashed var(--sp-border);
            border-radius: var(--sp-radius-lg);
        }

        .sp-empty-icon {
            font-size: 3rem;
            color: var(--sp-border);
            margin-bottom: 1rem;
        }

        /* ========================================
           RESPONSIVE
           ======================================== */
        @media (max-width: 767.98px) {
            .sp-hero {
                padding: 7rem 0 3rem;
                min-height: auto;
            }

            .sp-hero-title {
                font-size: 2rem;
            }

            .sp-hero-subtitle {
                font-size: 0.95rem;
            }

            .sp-search-bar {
                flex-wrap: wrap;
                border-radius: var(--sp-radius);
                padding: 4px;
            }

            .sp-search-bar .form-control {
                width: 100%;
                border-bottom: 1px solid var(--sp-border);
                border-radius: var(--sp-radius) var(--sp-radius) 0 0;
            }

            .sp-search-bar .form-select {
                border-left: none;
                border-bottom: 1px solid var(--sp-border);
                max-width: none;
                flex: 1;
            }

            .sp-search-bar .btn-search {
                width: 100%;
                justify-content: center;
                padding: 0.75rem;
                border-radius: 0 0 10px 10px;
            }

            .sp-stats {
                gap: 1.5rem;
            }

            .sp-stat-number {
                font-size: 1.35rem;
            }

            .sp-section-title {
                font-size: 1.25rem;
            }

            .sp-cta {
                padding: 2.5rem 1.25rem;
            }

            .sp-cta h2 {
                font-size: 1.4rem;
            }

            .sp-footer-bottom {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }

            /* Navbar mobile styles handled by partials/navbar.blade.php */
        }
    </style>
</head>
<body>

    @include('partials.navbar', ['transparent' => true])

    {{-- ============================================
         2. HERO
         ============================================ --}}
    <section class="sp-hero">
        {{-- Background Video --}}
        <video class="sp-hero-video" autoplay muted loop playsinline poster="/video/hero-poster.jpg">
            <source src="/video/hero.mp4" type="video/mp4">
        </video>
        <div class="sp-hero-overlay"></div>

        <div class="container text-center">
            {{-- Trust pill --}}
            <div style="margin-bottom:1.25rem;">
                <span style="display:inline-flex;align-items:center;gap:.4rem;background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:99px;padding:.4rem 1rem;font-size:.8rem;color:rgba(255,255,255,0.9);font-weight:500;">
                    <span style="font-size:1rem;">🇱🇻</span> Latvijas sludinājumu portāls
                </span>
            </div>

            <h1 class="sp-hero-title">Pirkt & Pārdot<br>Latvijā</h1>
            <p class="sp-hero-subtitle">Atrodi labākos piedāvājumus no uzticamiem pārdevējiem visā Latvijā</p>

            {{-- Search Bar --}}
            <form action="{{ route('browse') }}" method="GET" class="sp-search-bar">
                <input type="text" name="search" class="form-control" placeholder="Ko tu meklē? Piem. BMW, dīvāns, MacBook...">
                <select name="category" class="form-select">
                    <option value="">Visas kategorijas</option>
                    @foreach($menus as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-search">
                    <i class="bi bi-search"></i> Meklēt
                </button>
            </form>

            {{-- Popular searches --}}
            <div style="display:flex;justify-content:center;gap:.4rem;flex-wrap:wrap;margin-bottom:2rem;">
                <span style="font-size:.78rem;color:rgba(255,255,255,0.5);padding:.35rem 0;margin-right:.25rem;">Populāri:</span>
                @foreach(['Auto', 'iPhone', 'Dīvāns', 'Velosipēds', 'Dzīvoklis'] as $tag)
                    <a href="{{ route('browse', ['search' => $tag]) }}" style="display:inline-block;background:rgba(255,255,255,0.1);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.85);font-size:.78rem;font-weight:500;padding:.3rem .85rem;border-radius:99px;text-decoration:none;transition:.2s;">{{ $tag }}</a>
                @endforeach
            </div>

            {{-- Stats --}}
            <div class="sp-stats">
                <div class="sp-stat">
                    <span class="sp-stat-number">{{ $totalAds }}</span>
                    <span class="sp-stat-label">Aktīvi sludinājumi</span>
                </div>
                <div class="sp-stat">
                    <span class="sp-stat-number">{{ $totalCategories }}</span>
                    <span class="sp-stat-label">Kategorijas</span>
                </div>
                <div class="sp-stat">
                    <span class="sp-stat-number">{{ $totalUsers }}</span>
                    <span class="sp-stat-label">Lietotāji</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================
         3. BROWSE BY CATEGORY
         ============================================ --}}
    <section class="sp-section">
        <div class="container">
            <div class="sp-section-header">
                <h2 class="sp-section-title">Pārlūkot pēc kategorijas</h2>
            </div>

            @php
                $catIcons = [
                    'Transports'             => 'bi-truck-front-fill',
                    'Nekustamais ipasums'    => 'bi-buildings-fill',
                    'Elektronikas'           => 'bi-cpu-fill',
                    'Majai un darzam'        => 'bi-tree-fill',
                    'Apgerbs'                => 'bi-bag-fill',
                    'Sports un hobiji'       => 'bi-dribbble',
                    'Dzivnieki'              => 'bi-bug-fill',
                    'Darbs'                  => 'bi-briefcase-fill',
                ];
                $defaultCatIcon = 'bi-tag-fill';
            @endphp

            <div class="row g-3">
                @foreach($menus as $i => $category)
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="{{ route('browse', ['category' => $category->id]) }}" class="sp-category-card">
                            <div class="sp-category-icon">
                                <i class="bi {{ $catIcons[$category->name] ?? $defaultCatIcon }}"></i>
                            </div>
                            <span class="sp-category-name">{{ $category->name }}</span>
                            <span class="sp-category-count">{{ $category->ads_count ?? $category->ads()->where('published', 1)->count() }} slud.</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================
         4. LATEST ADS
         ============================================ --}}
    <section class="sp-section" style="padding-top: 0;">
        <div class="container">
            <div class="sp-section-header">
                <h2 class="sp-section-title">Jaunākie sludinājumi</h2>
                <a href="{{ route('browse') }}" class="sp-section-link">
                    Skatīt visus <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($ads->count())
                <div class="row g-3">
                    @foreach($ads as $ad)
                        <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                            <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="sp-ad-card">
                                <div class="sp-ad-image">
                                    <img src="{{ str_starts_with($ad->feature_image, 'http') ? $ad->feature_image : route('ad.image', basename($ad->feature_image)) }}"
                                         alt="{{ $ad->name }}"
                                         loading="lazy">
                                    @if($ad->product_condition)
                                        <span class="sp-ad-badge {{ $ad->product_condition === 'new' ? 'sp-ad-badge-new' : 'sp-ad-badge-used' }}">
                                            {{ ucfirst($ad->product_condition) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="sp-ad-body">
                                    <div class="sp-ad-title">{{ Str::limit($ad->name, 40) }}</div>
                                    @if($ad->listing_location)
                                        <div class="sp-ad-location">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            {{ Str::limit($ad->listing_location, 28) }}
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
            @else
                <div class="sp-empty">
                    <div class="sp-empty-icon"><i class="bi bi-inbox"></i></div>
                    <h5 style="color: var(--sp-dark); font-weight: 600;">Vēl nav neviena sludinājuma</h5>
                    <p style="color: var(--sp-text-muted); font-size: 0.92rem;">Esi pirmais, kas kaut ko ievieto!</p>
                    <a href="{{ url('/ads/create') }}" class="btn btn-post-ad mt-2">
                        <i class="bi bi-plus-lg"></i> Ievietot sludinājumu
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================
         5. HOW IT WORKS
         ============================================ --}}
    <section class="sp-how-section">
        <div class="container">
            <div class="text-center">
                <span class="sp-how-badge">
                    <i class="bi bi-lightning-fill"></i> Vienkārši un ātri
                </span>
                <h2 class="sp-how-heading">Kā tas strādā?</h2>
                <p class="sp-how-subheading">Sāc pirkt vai pārdot tikai 3 vienkāršos soļos</p>
            </div>

            <div class="row g-4 sp-steps-row">
                <div class="col-md-4">
                    <div class="sp-step-card">
                        <div class="sp-step-icon-wrap" style="background: var(--sp-primary-light);">
                            <i class="bi bi-person-vcard-fill" style="color: var(--sp-primary);"></i>
                            <span class="sp-step-number">1</span>
                        </div>
                        <h6 class="sp-step-title">Izveido profilu</h6>
                        <p class="sp-step-desc">Reģistrējies bez maksas un ievadi savu informāciju dažu sekunžu laikā</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sp-step-card">
                        <div class="sp-step-icon-wrap" style="background: #fef3c7;">
                            <i class="bi bi-megaphone-fill" style="color: var(--sp-accent);"></i>
                            <span class="sp-step-number">2</span>
                        </div>
                        <h6 class="sp-step-title">Pievieno sludinājumu</h6>
                        <p class="sp-step-desc">Augšupielādē fotoattēlus, pievieno aprakstu un nosaki savu cenu</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sp-step-card">
                        <div class="sp-step-icon-wrap" style="background: #dcfce7;">
                            <i class="bi bi-trophy-fill" style="color: var(--sp-success);"></i>
                            <span class="sp-step-number">3</span>
                        </div>
                        <h6 class="sp-step-title">Noslēdz darījumu</h6>
                        <p class="sp-step-desc">Sazinies ar pircējiem vai pārdevējiem un vienojies par labāko piedāvājumu</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================
         6. CTA BANNER
         ============================================ --}}
    <section class="sp-section">
        <div class="container">
            <div class="sp-cta">
                <div class="sp-cta-content">
                    <h2>Gribi kaut ko pārdot?</h2>
                    <p>Ievieto savu sludinājumu jau šodien un sasniedz tūkstošiem potenciālo pircēju visā Latvijā</p>
                    @auth
                        <a href="{{ url('/ads/create') }}" class="btn-cta">
                            <i class="bi bi-plus-lg"></i> Ievietot bez maksas
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-cta">
                            <i class="bi bi-person-plus-fill"></i> Reģistrēties un ievietot
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================
         7. FOOTER
         ============================================ --}}
    <footer class="sp-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="sp-footer-brand">
                        <img src="/img/logo.png" alt="{{ config('app.name') }}" style="height:28px;width:auto;filter:brightness(0) invert(1);">
                    </div>
                    <p style="font-size: 0.88rem; line-height: 1.6; margin-bottom: 0;">
                        Tavs uzticamais sludinājumu portāls Latvijā. Pērc un pārdod droši — no elektronikas līdz nekustamajam īpašumam.
                    </p>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6>Ātrās saites</h6>
                    <ul class="sp-footer-links">
                        <li><a href="{{ url('/home') }}">Sākums</a></li>
                        <li><a href="{{ route('browse') }}">Sludinājumi</a></li>
                        <li><a href="{{ url('/ads/create') }}">Ievietot sludinājumu</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6>Kategorijas</h6>
                    <ul class="sp-footer-links">
                        @foreach($menus->take(5) as $menu)
                            <li><a href="{{ route('browse', ['category' => $menu->id]) }}">{{ $menu->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6>Konts</h6>
                    <ul class="sp-footer-links">
                        @guest
                            <li><a href="{{ route('login') }}">Ielogoties</a></li>
                            <li><a href="{{ route('register') }}">Reģistrēties</a></li>
                        @else
                            <li><a href="{{ url('/ads/create') }}">Ievietot sludinājumu</a></li>
                            <li><a href="{{ url('/ads') }}">Mani sludinājumi</a></li>
                            <li><a href="{{ route('profile') }}">Profils</a></li>
                        @endguest
                    </ul>
                </div>
            </div>

            <div class="sp-footer-bottom">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'SludinajumuPortals') }}. Visas tiesības aizsargātas.</span>
                <div class="sp-footer-social">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
