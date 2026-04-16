<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mani sludinājumi — {{ config('app.name', 'SludinajumuPortals') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--suc-lt:#dcfce7;--warn:#F59E0B;--warn-lt:#fef3c7;--dan:#EF4444;--bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t2:#334155;--t3:#64748b;--t4:#94a3b8;--t5:#cbd5e1;--bdr:#e2e8f0;--r:16px;--r-sm:12px;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--t2);-webkit-font-smoothing:antialiased;}

        /* Nav */
        .sp-nav{background:var(--wh);border-bottom:1px solid var(--bdr);position:sticky;top:0;z-index:100;box-shadow:0 1px 2px rgba(0,0,0,.05);}
        .sp-nav .navbar-brand{font-weight:800;font-size:1.25rem;color:var(--dk);display:flex;align-items:center;gap:.5rem;}
        .sp-nav .brand-sq{width:32px;height:32px;background:var(--pri);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;}
        .sp-nav .nav-link{color:var(--t2);font-weight:500;font-size:.87rem;padding:.4rem .7rem;border-radius:var(--r-xs);transition:.15s;}
        .sp-nav .nav-link:hover{color:var(--pri);background:var(--pri-lt);}
        .nav-post{background:var(--pri)!important;color:#fff!important;font-weight:600;border-radius:var(--r-xs);padding:.45rem 1rem!important;}

        /* Sidebar */
        .dash-sidebar{background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r);padding:1.25rem;position:sticky;top:80px;}
        .dash-avatar{width:56px;height:56px;border-radius:50%;background:var(--pri-lt);display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:1.5rem;}
        .dash-user-name{font-size:1rem;font-weight:700;color:var(--dk);}
        .dash-badge{display:inline-flex;align-items:center;gap:.25rem;background:var(--warn-lt);color:#92400e;font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:99px;}
        .dash-mini-stats{display:flex;gap:.75rem;margin:.75rem 0;padding:.6rem 0;border-top:1px solid var(--bdr);border-bottom:1px solid var(--bdr);}
        .dash-mini-stat{text-align:center;flex:1;}
        .dash-mini-stat-val{font-size:1rem;font-weight:800;color:var(--dk);}
        .dash-mini-stat-label{font-size:.65rem;color:var(--t4);text-transform:uppercase;letter-spacing:.3px;}
        .dash-nav{display:flex;flex-direction:column;gap:.2rem;margin-top:.5rem;}
        .dash-nav-item{display:flex;align-items:center;gap:.6rem;padding:.55rem .75rem;border-radius:var(--r-xs);font-size:.85rem;font-weight:500;color:var(--t2);text-decoration:none;transition:.15s;border-left:3px solid transparent;}
        .dash-nav-item:hover{background:var(--pri-lt);color:var(--pri);}
        .dash-nav-item.active{background:var(--pri-lt);color:var(--pri);border-left-color:var(--pri);font-weight:600;}
        .dash-nav-item i{font-size:1rem;width:20px;text-align:center;}
        .dash-nav-count{margin-left:auto;font-size:.72rem;font-weight:700;background:var(--bg);padding:.1rem .45rem;border-radius:99px;color:var(--t3);}

        /* Page header */
        .page-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .page-head h1{font-size:1.4rem;font-weight:800;color:var(--dk);margin:0;}
        .page-head-sub{font-size:.85rem;color:var(--t3);}
        .btn-new-ad{height:44px;padding:0 1.25rem;background:var(--pri);color:#fff;border:none;border-radius:var(--r-xs);font-weight:600;font-size:.88rem;display:inline-flex;align-items:center;gap:.4rem;cursor:pointer;transition:.15s;text-decoration:none;}
        .btn-new-ad:hover{background:var(--pri-dk);color:#fff;}

        /* Stats cards */
        .stat-card{background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r);padding:1.15rem;transition:.2s;}
        .stat-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);}
        .stat-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:.6rem;}
        .stat-val{font-size:1.6rem;font-weight:800;color:var(--dk);line-height:1;}
        .stat-label{font-size:.75rem;color:var(--t4);margin-top:.2rem;}

        /* Filters */
        .filter-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.75rem;}
        .filter-tabs{display:flex;gap:0;border:1.5px solid var(--bdr);border-radius:var(--r-xs);overflow:hidden;}
        .filter-tab{padding:.45rem 1rem;font-size:.82rem;font-weight:600;color:var(--t3);cursor:pointer;transition:.15s;border-right:1px solid var(--bdr);background:var(--wh);}
        .filter-tab:last-child{border-right:none;}
        .filter-tab:hover{background:var(--pri-lt);}
        .filter-tab.active{background:var(--pri);color:#fff;}
        .filter-search{height:40px;border:1.5px solid var(--bdr);border-radius:var(--r-xs);padding:0 .75rem 0 2.25rem;font-size:.85rem;width:220px;outline:none;transition:.2s;background:var(--wh) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242.156a5 5 0 1 1 0-10 5 5 0 0 1 0 10z'/%3E%3C/svg%3E") no-repeat .75rem center;}
        .filter-search:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}

        /* Listing cards */
        .listing-card{background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r);padding:1rem;display:flex;align-items:center;gap:1rem;margin-bottom:.6rem;transition:.2s;position:relative;}
        .listing-card:hover{border-color:rgba(37,99,235,.3);box-shadow:0 2px 8px rgba(0,0,0,.04);transform:translateX(2px);}
        .listing-img{width:88px;height:88px;border-radius:var(--r-sm);overflow:hidden;background:#f1f5f9;flex-shrink:0;position:relative;}
        .listing-img img{width:100%;height:100%;object-fit:cover;}
        .listing-status{position:absolute;top:6px;left:6px;padding:.15rem .45rem;border-radius:99px;font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.3px;}
        .status-published{background:var(--suc-lt);color:#16a34a;}
        .status-pending{background:var(--warn-lt);color:#d97706;}
        .status-expired{background:#f1f5f9;color:var(--t3);}
        .listing-info{flex:1;min-width:0;}
        .listing-title{font-size:.92rem;font-weight:600;color:var(--t1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:.2rem;}
        .listing-category{font-size:.72rem;color:var(--t4);margin-bottom:.3rem;}
        .listing-price{font-size:1.05rem;font-weight:700;color:var(--pri);}
        .listing-meta{font-size:.72rem;color:var(--t4);display:flex;gap:.75rem;margin-top:.25rem;}
        .listing-meta span{display:flex;align-items:center;gap:.2rem;}
        .listing-stats{display:flex;gap:1.25rem;flex-shrink:0;}
        .listing-stat{text-align:center;}
        .listing-stat-val{font-size:.95rem;font-weight:700;color:var(--t1);}
        .listing-stat-label{font-size:.62rem;color:var(--t4);text-transform:uppercase;}
        .listing-actions{flex-shrink:0;position:relative;}
        .kebab-btn{width:36px;height:36px;border-radius:8px;border:1px solid var(--bdr);background:var(--wh);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:var(--t3);transition:.15s;}
        .kebab-btn:hover{background:var(--pri-lt);color:var(--pri);border-color:var(--pri);}
        .kebab-menu{position:absolute;right:0;top:42px;background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r-sm);box-shadow:0 10px 30px rgba(0,0,0,.1);padding:.35rem;min-width:200px;z-index:50;display:none;}
        .kebab-menu.open{display:block;animation:menuIn .15s ease;}
        @keyframes menuIn{from{opacity:0;transform:scale(.95) translateY(-4px);}to{opacity:1;transform:scale(1) translateY(0);}}
        .kebab-item{display:flex;align-items:center;gap:.6rem;padding:.5rem .75rem;font-size:.84rem;color:var(--t2);border-radius:var(--r-xs);cursor:pointer;transition:.1s;text-decoration:none;border:none;background:none;width:100%;text-align:left;}
        .kebab-item:hover{background:var(--pri-lt);color:var(--pri);}
        .kebab-item.danger{color:var(--dan);}
        .kebab-item.danger:hover{background:#fef2f2;color:var(--dan);}
        .kebab-item i{width:18px;text-align:center;font-size:.95rem;}
        .kebab-divider{height:1px;background:var(--bdr);margin:.3rem .5rem;}

        /* Empty */
        .empty-state{text-align:center;padding:4rem 2rem;}
        .empty-icon{font-size:3.5rem;color:var(--t5);margin-bottom:1rem;}

        /* Delete Modal */
        .modal-backdrop-c{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);z-index:1000;display:none;align-items:center;justify-content:center;}
        .modal-backdrop-c.open{display:flex;}
        .modal-card{background:var(--wh);border-radius:var(--r);max-width:420px;width:90%;padding:2rem;text-align:center;animation:menuIn .2s ease;}
        .modal-card h3{font-size:1.1rem;font-weight:700;color:var(--dk);margin:.75rem 0 .5rem;}

        /* Toast */
        .toast-wrap{position:fixed;top:20px;right:20px;z-index:3000;}
        .toast-msg{background:var(--t1);color:#fff;padding:.65rem 1.1rem;border-radius:var(--r-xs);font-size:.85rem;font-weight:500;box-shadow:0 12px 32px rgba(0,0,0,.1);animation:toastIn .3s ease,toastOut .3s ease 2.7s forwards;}
        @keyframes toastIn{from{opacity:0;transform:translateX(30px);}to{opacity:1;transform:translateX(0);}}
        @keyframes toastOut{from{opacity:1;}to{opacity:0;}}

        /* Footer */
        .sp-foot{background:var(--dk);color:rgba(255,255,255,.6);padding:2rem 0 1rem;margin-top:3rem;}
        .sp-foot a{color:rgba(255,255,255,.5);text-decoration:none;font-size:.83rem;}

        /* Animation */
        .fade-in{opacity:0;transform:translateY(12px);animation:fadeUp .4s ease forwards;}
        @keyframes fadeUp{to{opacity:1;transform:translateY(0);}}

        @media(max-width:991.98px){.dash-sidebar-col{display:none;}}
        @media(max-width:767.98px){
            .filter-bar{flex-direction:column;align-items:stretch;}
            .filter-tabs{overflow-x:auto;}
            .filter-search{width:100%;}
            .listing-stats{display:none;}
            .listing-card{padding:.75rem;gap:.75rem;}
            .listing-img{width:72px;height:72px;}
            .stat-val{font-size:1.3rem;}
        }
    </style>
</head>
<body>
    <div class="toast-wrap" id="toastWrap"></div>

    {{-- Delete Modal --}}
    <div class="modal-backdrop-c" id="deleteModal">
        <div class="modal-card">
            <div style="font-size:2.5rem;color:var(--dan);"><i class="bi bi-exclamation-triangle"></i></div>
            <h3>Dzēst sludinājumu?</h3>
            <p style="font-size:.88rem;color:var(--t3);margin-bottom:.5rem;" id="deleteAdName"></p>
            <p style="font-size:.8rem;color:var(--t4);">Šī darbība ir neatgriezeniska. Sludinājums un visi saistītie dati tiks dzēsti.</p>
            <div style="display:flex;gap:.5rem;margin-top:1.25rem;justify-content:center;">
                <button class="kebab-item" style="width:auto;padding:.55rem 1.25rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-weight:600;" onclick="closeDeleteModal()">Atcelt</button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" style="padding:.55rem 1.25rem;background:var(--dan);color:#fff;border:none;border-radius:var(--r-xs);font-weight:600;font-size:.88rem;cursor:pointer;">
                        <i class="bi bi-trash3 me-1"></i> Dzēst
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg sp-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/home') }}">
                <span class="brand-sq"><i class="bi bi-megaphone-fill"></i></span>
                {{ config('app.name', 'SludinajumuPortals') }}
            </a>
            <div class="navbar-nav ms-auto align-items-center gap-1">
                <a class="nav-link" href="{{ url('/home') }}"><i class="bi bi-house-door me-1"></i> Sākums</a>
                <a class="nav-link" href="{{ route('browse') }}"><i class="bi bi-collection me-1"></i> Sludinājumi</a>
                <a class="nav-link nav-post" href="{{ url('/ads/create') }}"><i class="bi bi-plus-lg me-1"></i> Ievietot</a>
            </div>
        </div>
    </nav>

    @php
        $activeAds = $ads->where('published', 1);
        $pendingAds = $ads->where('published', 0);
        $totalCount = $ads->count();
        $activeCount = $activeAds->count();
        $pendingCount = $pendingAds->count();
    @endphp

    <div class="container" style="margin-top:1.5rem;margin-bottom:2rem;">
        <div class="row g-4">
            {{-- Sidebar --}}
            <div class="col-lg-3 dash-sidebar-col">
                <div class="dash-sidebar">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="dash-avatar"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div class="dash-user-name">{{ auth()->user()->name }}</div>
                            <span class="dash-badge"><i class="bi bi-gem"></i> Lietotājs</span>
                        </div>
                    </div>
                    <div class="dash-mini-stats">
                        <div class="dash-mini-stat">
                            <div class="dash-mini-stat-val">{{ $activeCount }}</div>
                            <div class="dash-mini-stat-label">Aktīvi</div>
                        </div>
                        <div class="dash-mini-stat">
                            <div class="dash-mini-stat-val">{{ $pendingCount }}</div>
                            <div class="dash-mini-stat-label">Gaida</div>
                        </div>
                        <div class="dash-mini-stat">
                            <div class="dash-mini-stat-val">{{ $totalCount }}</div>
                            <div class="dash-mini-stat-label">Kopā</div>
                        </div>
                    </div>
                    <div class="dash-nav">
                        <a href="{{ route('ads.index') }}" class="dash-nav-item active">
                            <i class="bi bi-collection"></i> Mani sludinājumi
                            <span class="dash-nav-count">{{ $totalCount }}</span>
                        </a>
                        <a href="{{ url('/ads/create') }}" class="dash-nav-item">
                            <i class="bi bi-plus-circle"></i> Jauns sludinājums
                        </a>
                        <a href="{{ route('profile') }}" class="dash-nav-item">
                            <i class="bi bi-person"></i> Profils
                        </a>
                        <a href="{{ url('/home') }}" class="dash-nav-item">
                            <i class="bi bi-house-door"></i> Sākumlapa
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main --}}
            <div class="col-lg-9">
                {{-- Success message --}}
                @if(session('message') || session('success'))
                    <div style="background:var(--suc-lt);border:1px solid #a7f3d0;border-radius:var(--r-sm);padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
                        <i class="bi bi-check-circle-fill" style="color:var(--suc);"></i>
                        <span style="font-size:.88rem;color:#065f46;">{{ session('message') ?? session('success') }}</span>
                    </div>
                @endif

                {{-- Page Header --}}
                <div class="page-head">
                    <div>
                        <h1>Mani sludinājumi</h1>
                        <div class="page-head-sub">{{ $activeCount }} aktīvi, {{ $pendingCount }} gaida apstiprinājumu</div>
                    </div>
                    <a href="{{ url('/ads/create') }}" class="btn-new-ad">
                        <i class="bi bi-plus-lg"></i> Ievietot jaunu
                    </a>
                </div>

                {{-- Stats Overview --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="stat-card fade-in">
                            <div class="stat-icon" style="background:var(--pri-lt);color:var(--pri);"><i class="bi bi-collection-fill"></i></div>
                            <div class="stat-val">{{ $totalCount }}</div>
                            <div class="stat-label">Kopā sludinājumi</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card fade-in" style="animation-delay:.05s;">
                            <div class="stat-icon" style="background:var(--suc-lt);color:var(--suc);"><i class="bi bi-check-circle-fill"></i></div>
                            <div class="stat-val">{{ $activeCount }}</div>
                            <div class="stat-label">Aktīvi</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card fade-in" style="animation-delay:.1s;">
                            <div class="stat-icon" style="background:var(--warn-lt);color:var(--warn);"><i class="bi bi-clock-fill"></i></div>
                            <div class="stat-val">{{ $pendingCount }}</div>
                            <div class="stat-label">Gaida apstiprinājumu</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card fade-in" style="animation-delay:.15s;">
                            <div class="stat-icon" style="background:#fce7f3;color:#ec4899;"><i class="bi bi-heart-fill"></i></div>
                            <div class="stat-val">—</div>
                            <div class="stat-label">Saglabājumi</div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="filter-bar">
                    <div class="filter-tabs">
                        <div class="filter-tab active" onclick="filterAds(this,'all')">Visi ({{ $totalCount }})</div>
                        <div class="filter-tab" onclick="filterAds(this,'published')">Aktīvi ({{ $activeCount }})</div>
                        <div class="filter-tab" onclick="filterAds(this,'pending')">Gaida ({{ $pendingCount }})</div>
                    </div>
                    <input type="text" class="filter-search" placeholder="Meklēt sludinājumos..." oninput="searchAds(this.value)">
                </div>

                {{-- Listings --}}
                <div id="listingsContainer">
                    @forelse($ads as $ad)
                        <div class="listing-card fade-in" style="animation-delay:{{ $loop->index * 0.03 }}s;" data-status="{{ $ad->published ? 'published' : 'pending' }}" data-name="{{ strtolower($ad->name) }}">
                            {{-- Image --}}
                            <div class="listing-img">
                                @if($ad->feature_image)
                                    <img src="{{ str_starts_with($ad->feature_image, 'http') ? $ad->feature_image : route('ad.image', basename($ad->feature_image)) }}" alt="{{ $ad->name }}" loading="lazy">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100"><i class="bi bi-image" style="font-size:1.5rem;color:var(--t5);"></i></div>
                                @endif
                                <span class="listing-status {{ $ad->published ? 'status-published' : 'status-pending' }}">
                                    {{ $ad->published ? 'Aktīvs' : 'Gaida' }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <div class="listing-info">
                                <div class="listing-title">{{ $ad->name }}</div>
                                <div class="listing-category">
                                    {{ $ad->category->name ?? '' }}
                                    @if($ad->subcategory) &rsaquo; {{ $ad->subcategory->name }} @endif
                                </div>
                                <div class="listing-price">&euro;{{ number_format($ad->price, 2) }}</div>
                                <div class="listing-meta">
                                    @if($ad->listing_location)
                                        <span><i class="bi bi-geo-alt-fill"></i> {{ $ad->listing_location }}</span>
                                    @endif
                                    <span><i class="bi bi-clock"></i> {{ $ad->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="listing-stats">
                                <div class="listing-stat">
                                    <div class="listing-stat-val">—</div>
                                    <div class="listing-stat-label">Skatīj.</div>
                                </div>
                                <div class="listing-stat">
                                    <div class="listing-stat-val">—</div>
                                    <div class="listing-stat-label">Ziņas</div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="listing-actions">
                                <button class="kebab-btn" onclick="toggleKebab(event, this)" aria-label="Darbības">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="kebab-menu">
                                    <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="kebab-item">
                                        <i class="bi bi-eye"></i> Skatīt
                                    </a>
                                    <a href="{{ route('ads.edit', $ad->id) }}" class="kebab-item">
                                        <i class="bi bi-pencil"></i> Rediģēt
                                    </a>
                                    <a href="{{ url('/ads/create') }}" class="kebab-item">
                                        <i class="bi bi-copy"></i> Dublēt
                                    </a>
                                    <button class="kebab-item" onclick="shareAd('{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}')">
                                        <i class="bi bi-share"></i> Dalīties
                                    </button>
                                    <div class="kebab-divider"></div>
                                    <button class="kebab-item danger" onclick="openDeleteModal({{ $ad->id }}, '{{ addslashes($ad->name) }}')">
                                        <i class="bi bi-trash3"></i> Dzēst
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                            <h4 style="font-weight:700;color:var(--dk);margin-bottom:.5rem;">Tu vēl neesi ievietojis nevienu sludinājumu</h4>
                            <p style="font-size:.9rem;color:var(--t3);margin-bottom:1.25rem;">Pirmā sludinājuma ievietošana aizņem tikai 2 minūtes</p>
                            <a href="{{ url('/ads/create') }}" class="btn-new-ad" style="display:inline-flex;">
                                <i class="bi bi-plus-lg"></i> Ievietot sludinājumu
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <footer class="sp-foot">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span style="font-size:.8rem;">&copy; {{ date('Y') }} {{ config('app.name') }}. Visas tiesības aizsargātas.</span>
                <div class="d-flex gap-3">
                    <a href="{{ url('/home') }}">Sākums</a>
                    <a href="{{ route('browse') }}">Sludinājumi</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Kebab menu
    function toggleKebab(e, btn) {
        e.stopPropagation();
        var menu = btn.nextElementSibling;
        var wasOpen = menu.classList.contains('open');
        document.querySelectorAll('.kebab-menu.open').forEach(function(m){ m.classList.remove('open'); });
        if (!wasOpen) menu.classList.add('open');
    }
    document.addEventListener('click', function(){ document.querySelectorAll('.kebab-menu.open').forEach(function(m){ m.classList.remove('open'); }); });

    // Filter
    function filterAds(el, status) {
        document.querySelectorAll('.filter-tab').forEach(function(t){ t.classList.remove('active'); });
        el.classList.add('active');
        document.querySelectorAll('.listing-card').forEach(function(card) {
            if (status === 'all') { card.style.display = 'flex'; return; }
            card.style.display = card.dataset.status === status ? 'flex' : 'none';
        });
    }

    // Search
    function searchAds(q) {
        q = q.toLowerCase();
        document.querySelectorAll('.listing-card').forEach(function(card) {
            card.style.display = card.dataset.name.includes(q) ? 'flex' : 'none';
        });
    }

    // Delete modal
    function openDeleteModal(id, name) {
        document.querySelectorAll('.kebab-menu.open').forEach(function(m){ m.classList.remove('open'); });
        document.getElementById('deleteAdName').textContent = '"' + name + '"';
        document.getElementById('deleteForm').action = '/ads/' + id;
        document.getElementById('deleteModal').classList.add('open');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('open');
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Share
    function shareAd(url) {
        if (navigator.share) { navigator.share({ url: url }); }
        else { navigator.clipboard?.writeText(url); showToast('Saite nokopēta!'); }
    }

    // Toast
    function showToast(msg) {
        var el = document.createElement('div'); el.className = 'toast-msg'; el.textContent = msg;
        document.getElementById('toastWrap').appendChild(el);
        setTimeout(function(){ el.remove(); }, 3200);
    }
    </script>
</body>
</html>
