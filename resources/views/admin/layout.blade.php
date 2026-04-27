<!doctype html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin panelis') — {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --pri:#2563EB; --pri-dk:#1E40AF; --pri-lt:#eff6ff;
            --bg:#F8FAFC; --wh:#fff; --dk:#0f172a;
            --t1:#1e293b; --t2:#334155; --t3:#64748b; --t4:#94a3b8; --t5:#cbd5e1;
            --bdr:#e2e8f0; --suc:#10b981; --dan:#EF4444; --warn:#F59E0B;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--t2); margin: 0; min-height: 100vh; -webkit-font-smoothing: antialiased; }
        a { text-decoration: none; }

        .admin-layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .admin-sidebar {
            width: 240px; background: var(--wh); border-right: 1px solid var(--bdr);
            display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 1020;
        }
        .admin-sidebar .brand { padding: 1.25rem 1.25rem 1rem; display: flex; align-items: center; gap: .6rem; border-bottom: 1px solid var(--bdr); }
        .admin-sidebar .brand img { height: 32px; width: auto; }
        .admin-sidebar .brand .label { font-weight: 700; color: var(--dk); font-size: .95rem; }
        .admin-sidebar nav { padding: .75rem; flex: 1; overflow-y: auto; }
        .admin-sidebar nav .nav-section { font-size: .7rem; text-transform: uppercase; letter-spacing: .04em; color: var(--t4); padding: .75rem .75rem .35rem; font-weight: 600; }
        .admin-sidebar .side-link {
            display: flex; align-items: center; gap: .6rem; padding: .55rem .75rem; border-radius: 8px;
            font-size: .88rem; font-weight: 500; color: var(--t2); margin-bottom: .15rem; transition: .15s;
        }
        .admin-sidebar .side-link i { font-size: 1.05rem; }
        .admin-sidebar .side-link:hover { background: var(--pri-lt); color: var(--pri); }
        .admin-sidebar .side-link.active { background: var(--pri); color: #fff; }
        .admin-sidebar .side-link.active:hover { background: var(--pri-dk); color: #fff; }

        /* Main */
        .admin-main { flex: 1; margin-left: 240px; display: flex; flex-direction: column; }
        .admin-topbar {
            height: 64px; background: var(--wh); border-bottom: 1px solid var(--bdr);
            display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 1010;
        }
        .admin-topbar .title { font-size: 1rem; font-weight: 700; color: var(--dk); margin: 0; }
        .admin-topbar .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .admin-topbar .admin-name { font-size: .88rem; color: var(--t2); display: flex; align-items: center; gap: .5rem; }
        .admin-topbar .admin-name .avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--pri-lt); color: var(--pri); display: inline-flex; align-items: center; justify-content: center; font-weight: 600; }
        .admin-topbar .logout-btn {
            background: var(--wh); border: 1px solid var(--bdr); color: var(--t2);
            padding: .4rem .8rem; border-radius: 8px; font-size: .85rem; font-weight: 500;
            display: inline-flex; align-items: center; gap: .35rem; cursor: pointer; transition: .15s;
        }
        .admin-topbar .logout-btn:hover { background: #fef2f2; border-color: #fecaca; color: var(--dan); }

        .admin-content { padding: 1.5rem; flex: 1; }

        /* Cards / tables */
        .stat-card {
            background: var(--wh); border: 1px solid var(--bdr); border-radius: 12px;
            padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 1rem;
            transition: .15s;
        }
        .stat-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.05); transform: translateY(-1px); }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }
        .stat-card .stat-label { font-size: .78rem; color: var(--t3); font-weight: 500; text-transform: uppercase; letter-spacing: .03em; }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--dk); line-height: 1.1; }

        .panel { background: var(--wh); border: 1px solid var(--bdr); border-radius: 12px; overflow: hidden; }
        .panel-head { padding: .9rem 1.1rem; border-bottom: 1px solid var(--bdr); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .panel-head h2 { font-size: .98rem; font-weight: 700; margin: 0; color: var(--dk); }
        .panel-body { padding: 1.1rem; }

        .table { margin-bottom: 0; }
        .table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .03em; color: var(--t3); font-weight: 600; border-bottom-width: 1px; background: #f8fafc; white-space: nowrap; }
        .table td { font-size: .88rem; color: var(--t2); vertical-align: middle; }

        .badge-soft { font-size: .72rem; font-weight: 600; padding: .3rem .55rem; border-radius: 6px; }
        .badge-soft.bg-suc { background: #dcfce7; color: #166534; }
        .badge-soft.bg-dan { background: #fee2e2; color: #991b1b; }
        .badge-soft.bg-warn { background: #fef3c7; color: #92400e; }
        .badge-soft.bg-grey { background: #e2e8f0; color: #475569; }

        .btn-icon { padding: .35rem .6rem; font-size: .8rem; }

        .thumb { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--bdr); background: #f1f5f9; }
        .thumb-fallback { display: inline-flex; align-items: center; justify-content: center; color: var(--t4); font-size: 1rem; }

        .empty-state { padding: 2rem 1rem; text-align: center; color: var(--t4); font-size: .9rem; }

        @media (max-width: 991.98px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform .25s; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .admin-topbar .menu-toggle { display: inline-flex; }
        }
        .menu-toggle { display: none; background: var(--wh); border: 1px solid var(--bdr); width: 38px; height: 38px; border-radius: 8px; align-items: center; justify-content: center; }
    </style>
    @stack('head')
</head>
<body>
@php
    $adminUser = auth()->user();
    $current = request()->route()?->getName();
@endphp
<div class="admin-layout">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand">
            <img src="/img/logo.png" alt="{{ config('app.name') }}">
            <span class="label">Admin panelis</span>
        </div>
        <nav>
            <div class="nav-section">Pārvalde</div>
            <a href="{{ route('admin.dashboard') }}" class="side-link {{ $current === 'admin.dashboard' ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Sākums
            </a>
            <a href="{{ route('admin.users.index') }}" class="side-link {{ str_starts_with($current ?? '', 'admin.users') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Lietotāji
            </a>
            <a href="{{ route('admin.ads.index') }}" class="side-link {{ str_starts_with($current ?? '', 'admin.ads') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Sludinājumi
            </a>
            <a href="{{ route('category.index') }}" class="side-link {{ request()->is('auth/category*') || request()->is('auth/subcategory*') || request()->is('auth/childcategory*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Kategorijas
            </a>

            <div class="nav-section">Citi</div>
            <a href="{{ url('/home') }}" class="side-link">
                <i class="bi bi-arrow-left"></i> Atpakaļ uz vietni
            </a>
        </nav>
    </aside>

    <div class="admin-main">
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="menu-toggle" type="button" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="title">@yield('title', 'Admin panelis')</h1>
            </div>
            <div class="topbar-right">
                <div class="admin-name">
                    <span class="avatar">{{ strtoupper(mb_substr($adminUser->name ?? 'A', 0, 1)) }}</span>
                    <span class="d-none d-sm-inline">{{ $adminUser->name ?? '' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-left"></i> Iziet</button>
                </form>
            </div>
        </div>

        <main class="admin-content">
            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
