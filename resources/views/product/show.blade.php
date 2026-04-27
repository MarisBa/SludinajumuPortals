@php use Illuminate\Support\Str; @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $advertisement->name }} — {{ config('app.name', 'SludinajumuPortals') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">

    @php
        $images = $advertisement->getAllImages();
    @endphp

    @if(count($images))
        <link rel="preload" as="image" href="{{ str_starts_with($images[0], 'http') ? $images[0] : route('ad.image', basename($images[0])) }}" fetchpriority="high">
    @endif

    <style>
        :root {
            --pri: #2563EB; --pri-dk: #1E40AF; --pri-lt: #eff6ff; --pri-ultra: #dbeafe;
            --acc: #F59E0B; --suc: #10b981; --suc-lt: #dcfce7; --warn-lt: #fef3c7;
            --bg: #F8FAFC; --wh: #fff; --dk: #0f172a;
            --t1: #1e293b; --t2: #334155; --t3: #64748b; --t4: #94a3b8; --t5: #cbd5e1;
            --bdr: #e2e8f0; --r: 16px; --r-sm: 12px; --r-xs: 8px;
            --sh: 0 1px 3px rgba(0,0,0,0.06); --sh-md: 0 4px 12px rgba(0,0,0,0.08); --sh-lg: 0 12px 32px rgba(0,0,0,0.1);
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--t2); -webkit-font-smoothing: antialiased; }
        .heading-font { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }

        /* ===== BREADCRUMB ===== */
        .bc { padding: 1rem 0 .25rem; display: flex; align-items: center; gap: .35rem; flex-wrap: wrap; }
        .bc a { color: var(--t4); text-decoration: none; font-size: .8rem; transition: .15s; }
        .bc a:hover { color: var(--pri); text-decoration: underline; }
        .bc .sep { color: var(--t5); font-size: .7rem; }
        .bc .cur { color: var(--t1); font-weight: 500; font-size: .8rem; }

        /* ===== GALLERY ===== */
        .gal-main { position: relative; border-radius: var(--r); overflow: hidden; background: #f1f5f9; aspect-ratio: 4/3; cursor: pointer; }
        .gal-main img { width: 100%; height: 100%; object-fit: cover; transition: opacity .3s; }
        .gal-main:hover img { opacity: .95; }
        .gal-badge { position: absolute; top: 14px; left: 14px; padding: .3rem .75rem; border-radius: 99px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
        .gal-badge-new { background: rgba(16,185,129,.9); color: #fff; }
        .gal-badge-used { background: rgba(255,255,255,.85); color: var(--t1); }
        .gal-count { position: absolute; bottom: 14px; right: 14px; background: rgba(0,0,0,.55); backdrop-filter: blur(4px); color: #fff; font-size: .73rem; font-weight: 600; padding: .3rem .7rem; border-radius: 99px; }
        .gal-actions { position: absolute; top: 14px; right: 14px; display: flex; gap: .5rem; }
        .gal-act-btn { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,.9); backdrop-filter: blur(8px); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: .2s; color: var(--t2); font-size: 1.1rem; }
        .gal-act-btn:hover { transform: scale(1.1); box-shadow: var(--sh-md); }
        .gal-act-btn:active { transform: scale(.95); }
        .gal-act-btn.fav-active { color: #ef4444; }
        .gal-act-btn.fav-active i::before { content: "\F415"; }
        .thumbs { display: flex; gap: .5rem; margin-top: .75rem; overflow-x: auto; padding-bottom: 4px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
        .thumbs::-webkit-scrollbar { height: 0; }
        .th { width: 76px; height: 57px; border-radius: 10px; overflow: hidden; cursor: pointer; border: 2.5px solid transparent; transition: .2s; opacity: .5; scroll-snap-align: start; flex-shrink: 0; }
        .th:hover, .th.act { border-color: var(--pri); opacity: 1; }
        .th img { width: 100%; height: 100%; object-fit: cover; }

        /* Lightbox */
        .lb-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.92); z-index: 2000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
        .lb-overlay.open { display: flex; }
        .lb-overlay img { max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: var(--r-sm); animation: lbIn .3s ease; }
        @keyframes lbIn { from { opacity: 0; transform: scale(.95); } to { opacity: 1; transform: scale(1); } }
        .lb-close { position: absolute; top: 20px; right: 24px; width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,.15); border: none; color: #fff; font-size: 1.3rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: .15s; }
        .lb-close:hover { background: rgba(255,255,255,.25); }
        .lb-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,.15); border: none; color: #fff; font-size: 1.3rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: .15s; }
        .lb-arrow:hover { background: rgba(255,255,255,.3); }
        .lb-prev { left: 20px; }
        .lb-next { right: 20px; }
        .lb-counter { position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,.7); font-size: .85rem; font-weight: 500; }

        /* ===== CARDS ===== */
        .card-s { background: var(--wh); border: 1px solid var(--bdr); border-radius: var(--r); transition: .2s; }

        /* ===== PRICE CARD ===== */
        .price-val { font-size: 2.25rem; font-weight: 800; color: var(--pri); letter-spacing: -.5px; line-height: 1.1; }
        .price-cur { font-size: 1.5rem; color: var(--t4); font-weight: 600; }
        .price-pill { display: inline-block; background: var(--pri-lt); color: var(--pri); font-size: .7rem; font-weight: 700; padding: .2rem .6rem; border-radius: 99px; text-transform: uppercase; vertical-align: middle; margin-left: .4rem; }
        .meta-row { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: .85rem; padding-top: .85rem; border-top: 1px solid var(--bdr); }
        .meta-item { display: flex; align-items: center; gap: .35rem; font-size: .8rem; color: var(--t3); }
        .meta-item i { font-size: .9rem; color: var(--t4); }

        /* CTA */
        .btn-cta { width: 100%; height: 48px; border-radius: var(--r-sm); font-weight: 600; font-size: .92rem; display: flex; align-items: center; justify-content: center; gap: .5rem; border: none; cursor: pointer; transition: .15s; text-decoration: none; }
        .btn-cta:active { transform: scale(.98); }
        .btn-pri { background: var(--suc); color: #fff; }
        .btn-pri:hover { background: #059669; color: #fff; }
        .btn-sec { background: var(--pri); color: #fff; }
        .btn-sec:hover { background: var(--pri-dk); color: #fff; }
        .btn-out { background: none; border: 2px solid var(--bdr); color: var(--t2); }
        .btn-out:hover { border-color: var(--pri); color: var(--pri); background: var(--pri-lt); }
        .phone-blur { filter: blur(5px); transition: filter .4s; user-select: none; }
        .phone-clear { filter: blur(0); }

        /* ===== SELLER ===== */
        .seller-av { width: 52px; height: 52px; border-radius: 50%; background: var(--pri-lt); display: flex; align-items: center; justify-content: center; color: var(--pri); font-size: 1.4rem; flex-shrink: 0; position: relative; }
        .seller-dot { position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; border-radius: 50%; background: var(--suc); border: 2px solid var(--wh); }
        .seller-stats { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; margin-top: .75rem; }
        .seller-stat { background: var(--bg); border-radius: var(--r-xs); padding: .55rem .7rem; }
        .seller-stat-label { font-size: .68rem; color: var(--t4); text-transform: uppercase; letter-spacing: .4px; font-weight: 600; }
        .seller-stat-val { font-size: .9rem; font-weight: 700; color: var(--t1); }

        /* ===== TRUST ===== */
        .trust-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .5rem; }
        .trust-item { display: flex; align-items: flex-start; gap: .6rem; padding: .75rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--r-sm); }
        .trust-icon { width: 36px; height: 36px; border-radius: 10px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 1rem; flex-shrink: 0; }
        .trust-text { font-size: .78rem; color: #92400e; font-weight: 500; line-height: 1.4; }

        /* ===== DESCRIPTION ===== */
        .desc-wrap { position: relative; overflow: hidden; transition: max-height .4s ease; }
        .desc-wrap.collapsed { max-height: 140px; }
        .desc-fade { position: absolute; bottom: 0; left: 0; right: 0; height: 60px; background: linear-gradient(transparent, var(--wh)); pointer-events: none; }
        .desc-text { font-size: .92rem; line-height: 1.85; color: var(--t2); white-space: pre-line; }
        .btn-show-more { background: none; border: none; color: var(--pri); font-weight: 600; font-size: .88rem; cursor: pointer; padding: .5rem 0; display: flex; align-items: center; gap: .3rem; transition: .15s; }
        .btn-show-more:hover { gap: .5rem; }

        /* ===== DETAILS ===== */
        .det-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
        .det-item { padding: .75rem .9rem; background: var(--bg); border-radius: var(--r-xs); transition: .2s; }
        .det-item:hover { background: var(--pri-ultra); }
        .det-label { font-size: .68rem; font-weight: 600; color: var(--t4); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .15rem; display: flex; align-items: center; gap: .3rem; }
        .det-val { font-size: .88rem; font-weight: 600; color: var(--t1); }
        .cond-pill { display: inline-flex; align-items: center; gap: .25rem; padding: .2rem .6rem; border-radius: 99px; font-size: .75rem; font-weight: 700; }
        .cond-new { background: var(--suc-lt); color: #16a34a; }
        .cond-used { background: var(--warn-lt); color: #d97706; }

        /* ===== MAP ===== */
        #adMap { height: 340px; width: 100%; z-index: 1; }
        .map-loading { height: 340px; display: flex; align-items: center; justify-content: center; color: var(--t4); gap: .5rem; }

        /* ===== SECTION TITLE ===== */
        .sec-t { font-size: 1.05rem; font-weight: 700; color: var(--t1); display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .sec-t i { color: var(--pri); font-size: 1.1rem; }

        /* ===== SIMILAR / SELLER ADS ===== */
        .scroll-row { display: flex; gap: .75rem; overflow-x: auto; padding-bottom: 8px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
        .scroll-row::-webkit-scrollbar { height: 0; }
        .mini-card { min-width: 220px; max-width: 260px; flex-shrink: 0; scroll-snap-align: start; background: var(--wh); border: 1px solid var(--bdr); border-radius: var(--r-sm); overflow: hidden; text-decoration: none; transition: .25s; display: flex; flex-direction: column; }
        .mini-card:hover { transform: translateY(-3px); box-shadow: var(--sh-lg); border-color: transparent; }
        .mini-card-img { aspect-ratio: 4/3; overflow: hidden; background: #f1f5f9; }
        .mini-card-img img { width: 100%; height: 100%; object-fit: cover; transition: .3s; }
        .mini-card:hover .mini-card-img img { transform: scale(1.05); }
        .mini-card-body { padding: .75rem; flex: 1; display: flex; flex-direction: column; }
        .mini-card-title { font-size: .82rem; font-weight: 600; color: var(--t1); line-height: 1.3; margin-bottom: .3rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .mini-card-loc { font-size: .72rem; color: var(--t4); display: flex; align-items: center; gap: .2rem; margin-bottom: .4rem; }
        .mini-card-price { font-size: 1rem; font-weight: 700; color: var(--pri); margin-top: auto; }

        /* ===== STICKY SIDEBAR ===== */
        @media (min-width: 992px) {
            .sticky-rail { position: sticky; top: 80px; }
        }

        /* ===== MOBILE ACTION BAR ===== */
        .mob-bar { display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 200; background: var(--wh); border-top: 1px solid var(--bdr); padding: .75rem 1rem; box-shadow: 0 -4px 16px rgba(0,0,0,.08); }
        .mob-bar-price { font-size: 1.15rem; font-weight: 800; color: var(--pri); }
        .mob-bar-btns { display: flex; gap: .5rem; }
        .mob-bar-btn { flex: 1; height: 44px; border-radius: var(--r-xs); font-weight: 600; font-size: .85rem; border: none; display: flex; align-items: center; justify-content: center; gap: .4rem; cursor: pointer; text-decoration: none; }

        /* ===== TOAST ===== */
        .toast-wrap { position: fixed; top: 20px; right: 20px; z-index: 3000; display: flex; flex-direction: column; gap: .5rem; pointer-events: none; }
        .toast-msg { background: var(--t1); color: #fff; padding: .65rem 1.1rem; border-radius: var(--r-xs); font-size: .85rem; font-weight: 500; box-shadow: var(--sh-lg); animation: toastIn .3s ease, toastOut .3s ease 2.7s forwards; pointer-events: auto; }
        @keyframes toastIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes toastOut { from { opacity: 1; } to { opacity: 0; transform: translateY(-10px); } }

        /* ===== FOOTER ===== */
        .sp-foot { background: var(--dk); color: rgba(255,255,255,.6); padding: 2rem 0 1rem; margin-top: 3rem; }
        .sp-foot a { color: rgba(255,255,255,.5); text-decoration: none; font-size: .83rem; }
        .sp-foot a:hover { color: var(--acc); }

        /* ===== ANIMATIONS ===== */
        .fade-up { opacity: 0; transform: translateY(16px); transition: opacity .5s ease, transform .5s ease; }
        .fade-up.vis { opacity: 1; transform: translateY(0); }
        @media (prefers-reduced-motion: reduce) { .fade-up { opacity: 1; transform: none; transition: none; } }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .mob-bar { display: block; }
            body { padding-bottom: 90px; }
            .price-val { font-size: 1.75rem; }
        }
        @media (max-width: 767.98px) {
            .det-grid { grid-template-columns: 1fr; }
            .trust-strip { grid-template-columns: 1fr; }
            .gal-main { border-radius: 0; margin-left: -12px; margin-right: -12px; }
        }
    </style>
</head>
<body>

    {{-- Toast container --}}
    <div class="toast-wrap" id="toastWrap"></div>

    {{-- Lightbox --}}
    <div class="lb-overlay" id="lightbox">
        <button class="lb-close" onclick="closeLb()" aria-label="Aizvērt"><i class="bi bi-x-lg"></i></button>
        @if(count($images) > 1)
            <button class="lb-arrow lb-prev" onclick="lbNav(-1)" aria-label="Iepriekšējais"><i class="bi bi-chevron-left"></i></button>
            <button class="lb-arrow lb-next" onclick="lbNav(1)" aria-label="Nākamais"><i class="bi bi-chevron-right"></i></button>
        @endif
        <img id="lbImg" src="" alt="{{ $advertisement->name }}">
        <div class="lb-counter"><span id="lbIdx">1</span> / {{ count($images) }}</div>
    </div>

    @include('partials.navbar')

    <div class="container" style="padding-bottom: 2rem;">

        {{-- 1. Breadcrumb --}}
        <div class="bc">
            <a href="{{ url('/home') }}">Sākums</a>
            <i class="bi bi-chevron-right sep"></i>
            <a href="{{ route('browse') }}">Sludinājumi</a>
            @if($advertisement->category)
                <i class="bi bi-chevron-right sep"></i>
                <a href="{{ route('browse', ['category' => $advertisement->category_id]) }}">{{ $advertisement->category->name }}</a>
            @endif
            <i class="bi bi-chevron-right sep"></i>
            <span class="cur">{{ Str::limit($advertisement->name, 35) }}</span>
        </div>

        <div class="row g-4">
            {{-- ==================== LEFT COLUMN ==================== --}}
            <div class="col-lg-7">

                {{-- 2. Gallery --}}
                <div class="fade-up">
                    <div class="gal-main" onclick="openLb()" role="button" tabindex="0" aria-label="Atvērt galeriju">
                        @if(count($images))
                            <img src="{{ str_starts_with($images[0], 'http') ? $images[0] : route('ad.image', basename($images[0])) }}" alt="{{ $advertisement->name }}" id="mainImg" fetchpriority="high">
                            @if($advertisement->product_condition)
                                <span class="gal-badge {{ $advertisement->product_condition === 'new' ? 'gal-badge-new' : 'gal-badge-used' }}">
                                    {{ $advertisement->product_condition === 'new' ? 'Jauns' : 'Lietots' }}
                                </span>
                            @endif
                            <span class="gal-count"><i class="bi bi-images me-1"></i>{{ count($images) }}</span>
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100"><i class="bi bi-image" style="font-size: 3.5rem; color: var(--t5);"></i></div>
                        @endif
                        <div class="gal-actions" onclick="event.stopPropagation()">
                            <button class="gal-act-btn" onclick="toggleFav(this)" aria-label="Saglabāt"><i class="bi bi-heart"></i></button>
                            <button class="gal-act-btn" onclick="shareAd()" aria-label="Dalīties"><i class="bi bi-share"></i></button>
                        </div>
                    </div>

                    @if(count($images) > 1)
                        <div class="thumbs">
                            @foreach($images as $i => $img)
                                <div class="th {{ $i === 0 ? 'act' : '' }}" onclick="switchImg(this, {{ $i }})" role="button" tabindex="0">
                                    <img src="{{ str_starts_with($img, 'http') ? $img : route('ad.image', basename($img)) }}" alt="Foto {{ $i + 1 }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- 7. Description --}}
                <div class="card-s fade-up" style="padding: 1.5rem; margin-top: 1.25rem;">
                    <div class="sec-t"><i class="bi bi-text-paragraph"></i> Apraksts</div>
                    <div class="desc-wrap collapsed" id="descWrap">
                        <div class="desc-text">{{ $advertisement->description }}</div>
                        <div class="desc-fade" id="descFade"></div>
                    </div>
                    <button class="btn-show-more" id="descToggle" onclick="toggleDesc()">
                        Rādīt vairāk <i class="bi bi-chevron-down"></i>
                    </button>
                </div>

                {{-- 8. Details --}}
                <div class="card-s fade-up" style="padding: 1.5rem; margin-top: 1rem;">
                    <div class="sec-t"><i class="bi bi-info-circle"></i> Detaļas</div>
                    <div class="det-grid">
                        <div class="det-item">
                            <div class="det-label"><i class="bi bi-check2-circle"></i> Stāvoklis</div>
                            <div class="det-val">
                                @if($advertisement->product_condition === 'new')
                                    <span class="cond-pill cond-new"><i class="bi bi-check-circle-fill"></i> Jauns</span>
                                @else
                                    <span class="cond-pill cond-used"><i class="bi bi-arrow-repeat"></i> Lietots</span>
                                @endif
                            </div>
                        </div>
                        <div class="det-item">
                            <div class="det-label"><i class="bi bi-calendar3"></i> Publicēts</div>
                            <div class="det-val">{{ $advertisement->created_at->format('d.m.Y') }}</div>
                        </div>
                        @if($advertisement->category)
                        <div class="det-item">
                            <div class="det-label"><i class="bi bi-tag"></i> Kategorija</div>
                            <div class="det-val">{{ $advertisement->category->name }}</div>
                        </div>
                        @endif
                        <div class="det-item">
                            <div class="det-label"><i class="bi bi-globe2"></i> Valsts</div>
                            <div class="det-val">{{ $advertisement->country->name ?? '—' }}</div>
                        </div>
                        <div class="det-item">
                            <div class="det-label"><i class="bi bi-map"></i> Reģions</div>
                            <div class="det-val">{{ $advertisement->state->name ?? '—' }}</div>
                        </div>
                        @if($advertisement->listing_location)
                        <div class="det-item">
                            <div class="det-label"><i class="bi bi-geo-alt"></i> Atrašanās vieta</div>
                            <div class="det-val">{{ $advertisement->listing_location }}</div>
                        </div>
                        @endif
                        @if($advertisement->link)
                        <div class="det-item">
                            <div class="det-label"><i class="bi bi-link-45deg"></i> Saite</div>
                            <div class="det-val"><a href="{{ $advertisement->link }}" target="_blank" rel="noopener" style="color: var(--pri); text-decoration: none;">Atvērt <i class="bi bi-box-arrow-up-right"></i></a></div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- 10. Map --}}
                @if($advertisement->listing_location || $advertisement->city)
                <div class="card-s fade-up" style="overflow: hidden; margin-top: 1rem;">
                    <div style="padding: 1.25rem 1.5rem .75rem;">
                        <div class="sec-t"><i class="bi bi-geo-alt-fill"></i> Atrašanās vieta kartē</div>
                        <p style="font-size: .82rem; color: var(--t4); margin: 0;">
                            {{ $advertisement->listing_location ?? ($advertisement->city->name ?? '') }}, Latvija
                        </p>
                    </div>
                    <div id="adMap" style="margin-top: .5rem;">
                        <div class="map-loading"><i class="bi bi-geo-alt" style="font-size: 1.5rem;"></i> Ielādē karti...</div>
                    </div>
                    <div style="padding: .75rem 1.5rem; background: var(--bg); border-top: 1px solid var(--bdr); display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: .78rem; color: var(--t3);"><i class="bi bi-info-circle me-1"></i> Aptuvena atrašanās vieta</span>
                        <a href="https://www.google.com/maps/search/{{ urlencode(($advertisement->listing_location ?? '') . ', Latvia') }}" target="_blank" rel="noopener" style="font-size: .78rem; font-weight: 600; color: var(--pri); text-decoration: none;">
                            Norādes <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
                @endif

                {{-- 11. Similar Listings --}}
                @if($similarAds->count())
                <div class="fade-up" style="margin-top: 1.75rem;">
                    <div class="sec-t"><i class="bi bi-grid-3x3-gap"></i> Līdzīgi sludinājumi</div>
                    <div class="scroll-row">
                        @foreach($similarAds as $sim)
                            <a href="{{ route('product.view', ['id' => $sim->id, 'slug' => $sim->slug]) }}" class="mini-card">
                                <div class="mini-card-img">
                                    <img src="{{ str_starts_with($sim->feature_image, 'http') ? $sim->feature_image : route('ad.image', basename($sim->feature_image)) }}" alt="{{ $sim->name }}" loading="lazy">
                                </div>
                                <div class="mini-card-body">
                                    <div class="mini-card-title">{{ Str::limit($sim->name, 40) }}</div>
                                    @if($sim->listing_location)
                                        <div class="mini-card-loc"><i class="bi bi-geo-alt-fill"></i> {{ Str::limit($sim->listing_location, 20) }}</div>
                                    @endif
                                    <div class="mini-card-price">&euro;{{ number_format($sim->price, 2) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- 12. Seller's Other Listings --}}
                @if($sellerAds->count())
                <div class="fade-up" style="margin-top: 1.75rem;">
                    <div class="sec-t"><i class="bi bi-person-lines-fill"></i> Vairāk no {{ $advertisement->user->name ?? 'pārdevēja' }}</div>
                    <div class="scroll-row">
                        @foreach($sellerAds as $sa)
                            <a href="{{ route('product.view', ['id' => $sa->id, 'slug' => $sa->slug]) }}" class="mini-card">
                                <div class="mini-card-img">
                                    <img src="{{ str_starts_with($sa->feature_image, 'http') ? $sa->feature_image : route('ad.image', basename($sa->feature_image)) }}" alt="{{ $sa->name }}" loading="lazy">
                                </div>
                                <div class="mini-card-body">
                                    <div class="mini-card-title">{{ Str::limit($sa->name, 40) }}</div>
                                    <div class="mini-card-price">&euro;{{ number_format($sa->price, 2) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- ==================== RIGHT COLUMN (sticky) ==================== --}}
            <div class="col-lg-5">
                <div class="sticky-rail">

                    {{-- 3. Title + Price --}}
                    <div class="card-s fade-up" style="padding: 1.5rem;">
                        <h1 class="heading-font" style="font-size: 1.45rem; font-weight: 800; color: var(--dk); line-height: 1.3; margin-bottom: 1rem;">{{ $advertisement->name }}</h1>

                        <div style="padding-top: 1rem; border-top: 1px solid var(--bdr);">
                            <span class="price-cur">&euro;</span><span class="price-val">{{ number_format($advertisement->price, 2) }}</span>
                            @if($advertisement->price_status)
                                <span class="price-pill">{{ $advertisement->price_status }}</span>
                            @endif
                        </div>

                        <div class="meta-row">
                            @if($advertisement->listing_location)
                                <a href="#adMap" class="meta-item" style="text-decoration: none; cursor: pointer;" onclick="document.getElementById('adMap')?.scrollIntoView({behavior:'smooth',block:'center'})">
                                    <i class="bi bi-geo-alt-fill"></i> {{ $advertisement->listing_location }}
                                </a>
                            @endif
                            <span class="meta-item"><i class="bi bi-clock"></i> {{ $advertisement->created_at->diffForHumans() }}</span>
                            <span class="meta-item"><i class="bi bi-hash"></i> {{ $advertisement->id }}</span>
                        </div>

                        {{-- 4. CTA --}}
                        <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: .5rem;">
                            @auth
                                @if(auth()->id() !== $advertisement->user_id)
                                    <button type="button" class="btn-cta btn-sec" data-bs-toggle="modal" data-bs-target="#contactSellerModal">
                                        <i class="bi bi-chat-dots-fill"></i> Rakstīt pārdevējam
                                    </button>
                                @endif
                            @else
                                <a href="{{ url('/login') }}" class="btn-cta btn-sec">
                                    <i class="bi bi-chat-dots"></i> Pieslēdzies, lai rakstītu
                                </a>
                            @endauth
                            @if($advertisement->phone_number)
                                <button class="btn-cta btn-pri" id="phoneBtn" onclick="revealPhone()">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>Parādīt tālruni</span>
                                    <span class="phone-blur" id="phoneNum">{{ $advertisement->phone_number }}</span>
                                </button>
                            @endif
                            @if($advertisement->user->email ?? false)
                                <a href="mailto:{{ $advertisement->user->email }}?subject=Par: {{ urlencode($advertisement->name) }}" class="btn-cta btn-sec">
                                    <i class="bi bi-envelope-fill"></i> Rakstīt e-pastu
                                </a>
                            @endif
                        </div>

                        {{-- Tertiary actions --}}
                        <div style="display: flex; gap: .5rem; margin-top: .75rem;">
                            <button class="btn-cta btn-out" style="flex: 1; height: 40px; font-size: .82rem;" onclick="toggleFav(document.querySelector('.gal-act-btn'))">
                                <i class="bi bi-bookmark"></i> Saglabāt
                            </button>
                            <button class="btn-cta btn-out" style="flex: 1; height: 40px; font-size: .82rem;" onclick="shareAd()">
                                <i class="bi bi-share"></i> Dalīties
                            </button>
                        </div>

                        @auth
                            @if(auth()->id() === $advertisement->user_id || auth()->user()->isAdmin())
                                <div style="margin-top: .5rem;">
                                    <a href="{{ route('pdf.ad', $advertisement->id) }}"
                                       class="btn-cta btn-out"
                                       style="height: 40px; font-size: .82rem;"
                                       target="_blank">
                                        <i class="bi bi-file-pdf"></i> Lejupielādēt PDF
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>

                    {{-- 5. Seller --}}
                    <div class="card-s fade-up" style="padding: 1.5rem; margin-top: 1rem;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="seller-av">
                                <i class="bi bi-person-fill"></i>
                                <span class="seller-dot"></span>
                            </div>
                            <div>
                                <div style="font-size: 1rem; font-weight: 700; color: var(--t1);">{{ $advertisement->user->name ?? '—' }}</div>
                                <div style="font-size: .78rem; color: var(--t4);">Pārdevējs</div>
                            </div>
                        </div>
                        <div class="seller-stats">
                            <div class="seller-stat">
                                <div class="seller-stat-label">Dalībnieks kopš</div>
                                <div class="seller-stat-val">{{ $advertisement->user->created_at?->format('m.Y') ?? '—' }}</div>
                            </div>
                            <div class="seller-stat">
                                <div class="seller-stat-label">Aktīvi sludinājumi</div>
                                <div class="seller-stat-val">{{ \App\Models\Advertisement::where('user_id', $advertisement->user_id)->where('published', 1)->count() }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 6. Trust --}}
                    <div class="fade-up" style="margin-top: 1rem;">
                        <div class="trust-strip">
                            <div class="trust-item">
                                <div class="trust-icon"><i class="bi bi-people-fill"></i></div>
                                <span class="trust-text">Tiecieties publiskā vietā</span>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon"><i class="bi bi-search"></i></div>
                                <span class="trust-text">Pārbaudiet preci pirms pirkuma</span>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon"><i class="bi bi-shield-check"></i></div>
                                <span class="trust-text">Nemaksājiet iepriekš</span>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon"><i class="bi bi-flag"></i></div>
                                <span class="trust-text">Ziņojiet par aizdomīgiem sludinājumiem</span>
                            </div>
                        </div>
                    </div>

                    {{-- 14. Report --}}
                    <div class="fade-up" style="margin-top: 1rem; text-align: center; padding: .75rem;">
                        <span style="font-size: .8rem; color: var(--t4);">Pamanīji pārkāpumu?</span>
                        <button style="background: none; border: none; color: #ef4444; font-weight: 600; font-size: .8rem; cursor: pointer; margin-left: .3rem;" onclick="showToast('Paldies! Jūsu ziņojums tiks izskatīts.')">
                            <i class="bi bi-flag me-1"></i>Ziņot
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile bottom bar --}}
    <div class="mob-bar" id="mobBar">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="mob-bar-price">&euro;{{ number_format($advertisement->price, 2) }}</div>
                <div style="font-size: .72rem; color: var(--t4);">{{ Str::limit($advertisement->name, 25) }}</div>
            </div>
            <div class="mob-bar-btns">
                @if($advertisement->phone_number)
                    <a href="tel:{{ $advertisement->phone_number }}" class="mob-bar-btn" style="background: var(--suc); color: #fff;">
                        <i class="bi bi-telephone-fill"></i> Zvanīt
                    </a>
                @endif
                @if($advertisement->user->email ?? false)
                    <a href="mailto:{{ $advertisement->user->email }}" class="mob-bar-btn" style="background: var(--pri); color: #fff;">
                        <i class="bi bi-envelope-fill"></i> Rakstīt
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="sp-foot">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span style="font-size: .8rem;">&copy; {{ date('Y') }} {{ config('app.name') }}. Visas tiesības aizsargātas.</span>
                <div class="d-flex gap-3">
                    <a href="{{ url('/home') }}">Sākums</a>
                    <a href="{{ route('browse') }}">Sludinājumi</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    // === Image data ===
    var imgs = @json(collect($images)->map(fn($img) => str_starts_with($img, 'http') ? $img : route('ad.image', basename($img)))->values());
    var curIdx = 0;

    // === Gallery switch ===
    function switchImg(el, idx) {
        curIdx = idx;
        var main = document.getElementById('mainImg');
        if (main) { main.style.opacity = '0'; setTimeout(function(){ main.src = imgs[idx]; main.style.opacity = '1'; }, 150); }
        document.querySelectorAll('.th').forEach(function(t){ t.classList.remove('act'); });
        el.classList.add('act');
    }

    // === Lightbox ===
    function openLb() {
        if (!imgs.length) return;
        document.getElementById('lbImg').src = imgs[curIdx];
        document.getElementById('lbIdx').textContent = curIdx + 1;
        document.getElementById('lightbox').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeLb() {
        document.getElementById('lightbox').classList.remove('open');
        document.body.style.overflow = '';
    }
    function lbNav(dir) {
        curIdx = (curIdx + dir + imgs.length) % imgs.length;
        var lbImg = document.getElementById('lbImg');
        lbImg.style.opacity = '0';
        setTimeout(function(){ lbImg.src = imgs[curIdx]; lbImg.style.opacity = '1'; }, 150);
        document.getElementById('lbIdx').textContent = curIdx + 1;
        // Sync thumbs
        document.querySelectorAll('.th').forEach(function(t, i){ t.classList.toggle('act', i === curIdx); });
        var main = document.getElementById('mainImg');
        if (main) main.src = imgs[curIdx];
    }
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('open')) return;
        if (e.key === 'Escape') closeLb();
        if (e.key === 'ArrowLeft') lbNav(-1);
        if (e.key === 'ArrowRight') lbNav(1);
    });

    // === Phone reveal ===
    function revealPhone() {
        var num = document.getElementById('phoneNum');
        var btn = document.getElementById('phoneBtn');
        num.classList.add('phone-clear');
        setTimeout(function(){
            btn.outerHTML = '<a href="tel:{{ $advertisement->phone_number }}" class="btn-cta btn-pri"><i class="bi bi-telephone-fill"></i> {{ $advertisement->phone_number }}</a>';
        }, 500);
        navigator.clipboard?.writeText('{{ $advertisement->phone_number }}');
        showToast('Tālrunis nokopēts!');
    }

    // === Favorite ===
    function toggleFav(el) {
        el.classList.toggle('fav-active');
        showToast(el.classList.contains('fav-active') ? 'Saglabāts izlasē!' : 'Noņemts no izlases');
    }

    // === Share ===
    function shareAd() {
        if (navigator.share) {
            navigator.share({ title: @json($advertisement->name), url: window.location.href });
        } else {
            navigator.clipboard?.writeText(window.location.href);
            showToast('Saite nokopēta!');
        }
    }

    // === Description toggle ===
    function toggleDesc() {
        var w = document.getElementById('descWrap');
        var f = document.getElementById('descFade');
        var b = document.getElementById('descToggle');
        if (w.classList.contains('collapsed')) {
            w.classList.remove('collapsed');
            w.style.maxHeight = w.scrollHeight + 'px';
            f.style.display = 'none';
            b.innerHTML = 'Rādīt mazāk <i class="bi bi-chevron-up"></i>';
        } else {
            w.classList.add('collapsed');
            w.style.maxHeight = '140px';
            f.style.display = '';
            b.innerHTML = 'Rādīt vairāk <i class="bi bi-chevron-down"></i>';
        }
    }

    // === Toast ===
    function showToast(msg) {
        var el = document.createElement('div');
        el.className = 'toast-msg';
        el.textContent = msg;
        document.getElementById('toastWrap').appendChild(el);
        setTimeout(function(){ el.remove(); }, 3200);
    }

    // === Fade-up on scroll ===
    var obs = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(function(el) { obs.observe(el); });

    // === Mobile bar hide/show ===
    (function(){
        var last = 0, bar = document.getElementById('mobBar');
        if (!bar) return;
        window.addEventListener('scroll', function(){
            var cur = window.pageYOffset;
            bar.style.transform = cur > last && cur > 200 ? 'translateY(100%)' : 'translateY(0)';
            bar.style.transition = 'transform .3s';
            last = cur;
        });
    })();

    // === Map ===
    @if($advertisement->listing_location || $advertisement->city)
    document.addEventListener('DOMContentLoaded', function(){
        var mapEl = document.getElementById('adMap');
        if (!mapEl) return;

        var q = @json(
            ($advertisement->listing_location ?? ($advertisement->city->name ?? '')) . ', Latvia'
        );

        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(q) + '&limit=1')
            .then(function(r){ return r.json(); })
            .then(function(d){
                var lat = d.length ? parseFloat(d[0].lat) : 56.9496;
                var lng = d.length ? parseFloat(d[0].lon) : 24.1052;
                var z = d.length ? 13 : 7;
                mapEl.innerHTML = '';
                var map = L.map('adMap', {scrollWheelZoom: false}).setView([lat, lng], z);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'&copy; OpenStreetMap'}).addTo(map);
                L.circle([lat, lng], {radius: 500, color: '#2563EB', fillColor: '#2563EB', fillOpacity: 0.12, weight: 2}).addTo(map);
                L.marker([lat, lng]).addTo(map).bindPopup('<strong>{{ Str::limit($advertisement->name, 30) }}</strong><br><span style="color:#64748b;font-size:.8rem;">' + q + '</span>').openPopup();
                setTimeout(function(){ map.invalidateSize(); }, 200);
            })
            .catch(function(){ mapEl.innerHTML = '<div class="map-loading"><i class="bi bi-exclamation-triangle"></i> Neizdevās ielādēt karti</div>'; });
    });
    @endif
    </script>

    @auth
        @if(auth()->id() !== $advertisement->user_id)
        <div class="modal fade" id="contactSellerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-chat-dots-fill text-primary"></i>
                            Rakstīt pārdevējam
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
                    </div>
                    <form action="{{ url('/messages/start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="advertisement_id" value="{{ $advertisement->id }}">

                        <div class="modal-body">
                            <div class="alert alert-light border">
                                <div class="small text-muted">Sludinājums:</div>
                                <strong>{{ $advertisement->name }}</strong><br>
                                <span class="text-muted small">
                                    Pārdevējs: {{ $advertisement->user->name ?? 'Lietotājs' }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ātrās ziņas:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn">
                                        Sveiki! Vai vēl ir pieejams?
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn">
                                        Vai cena ir runājama?
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn">
                                        Vai varētu apskatīt klātienē?
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tava ziņa:</label>
                                <textarea name="body"
                                          id="contactSellerMessage"
                                          class="form-control"
                                          rows="4"
                                          maxlength="2000"
                                          required
                                          placeholder="Sveiki! Es interesējos par šo sludinājumu..."></textarea>
                                <small class="text-muted">Maksimums 2000 rakstzīmes</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Atcelt
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Sūtīt ziņu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const templateBtns = document.querySelectorAll('.template-btn');
                const messageInput = document.getElementById('contactSellerMessage');
                templateBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (messageInput) {
                            messageInput.value = btn.textContent.trim();
                            messageInput.focus();
                        }
                    });
                });
            });
        </script>
        @endif
    @endauth
</body>
</html>
