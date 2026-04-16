<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificē e-pastu — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t3:#64748b;--t4:#94a3b8;--bdr:#e2e8f0;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:#334155;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
        .v-card{width:100%;max-width:440px;text-align:center;animation:fadeUp .4s ease;}
        .v-icon{width:80px;height:80px;background:var(--pri-lt);border-radius:24px;display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:2.2rem;margin:0 auto 1.5rem;}
        .v-card h1{font-size:1.5rem;font-weight:800;color:var(--dk);margin-bottom:.5rem;}
        .v-card p{font-size:.9rem;color:var(--t3);margin-bottom:1.5rem;line-height:1.5;}
        .v-card p strong{color:var(--t1);}
        .btn-submit{width:100%;height:48px;background:var(--pri);color:#fff;border:none;border-radius:var(--r-xs);font-weight:600;font-size:.92rem;cursor:pointer;transition:.15s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
        .btn-submit:hover{background:var(--pri-dk);}
        .btn-out{width:100%;height:44px;background:none;border:1.5px solid var(--bdr);color:var(--t3);border-radius:var(--r-xs);font-weight:500;font-size:.88rem;cursor:pointer;transition:.15s;margin-top:.5rem;display:flex;align-items:center;justify-content:center;gap:.4rem;text-decoration:none;}
        .btn-out:hover{border-color:var(--pri);color:var(--pri);}
        .success-banner{background:#dcfce7;border:1px solid #a7f3d0;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#065f46;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
    </style>
</head>
<body>
    <div class="v-card">
        <a href="{{ url('/home') }}" style="font-size:.85rem;color:var(--t4);text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;margin-bottom:2rem;">
            <img src="/img/logo.png" alt="Logo" style="height:28px;width:auto;">
        </a>

        <div class="v-icon"><i class="bi bi-envelope-paper"></i></div>

        <h1>Pārbaudi savu e-pastu</h1>
        <p>Mēs nosūtījām verifikācijas saiti uz <strong>{{ auth()->user()->email ?? '' }}</strong>. Noklikšķini uz saites lai aktivizētu savu kontu.</p>

        @if(session('status'))
            <div class="success-banner"><i class="bi bi-check-circle-fill me-1"></i> Verifikācijas e-pasts nosūtīts!</div>
        @endif

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn-submit"><i class="bi bi-envelope"></i> Nosūtīt vēlreiz</button>
        </form>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-out"><i class="bi bi-box-arrow-left"></i> Iziet un mainīt e-pastu</button>
        </form>
    </div>
</body>
</html>
