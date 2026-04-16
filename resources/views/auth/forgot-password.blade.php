<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atjaunot paroli — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--dan:#EF4444;--bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t2:#334155;--t3:#64748b;--t4:#94a3b8;--bdr:#e2e8f0;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--t2);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
        .auth-card{width:100%;max-width:420px;animation:fadeUp .4s ease;}
        .auth-card h1{font-size:1.5rem;font-weight:800;color:var(--dk);margin-bottom:.3rem;}
        .auth-card .sub{font-size:.88rem;color:var(--t3);margin-bottom:1.5rem;}
        .auth-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;}
        .auth-top a{font-size:.85rem;color:var(--t3);text-decoration:none;display:flex;align-items:center;gap:.3rem;}.auth-top a:hover{color:var(--pri);}
        .f-label{font-size:.85rem;font-weight:600;color:var(--t1);margin-bottom:.35rem;display:block;}
        .f-input{width:100%;height:48px;padding:0 1rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-size:.9rem;color:var(--t1);transition:.2s;outline:none;}
        .f-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
        .f-group{margin-bottom:1rem;}
        .f-err{font-size:.73rem;color:var(--dan);margin-top:.3rem;}
        .btn-submit{width:100%;height:48px;background:var(--pri);color:#fff;border:none;border-radius:var(--r-xs);font-weight:600;font-size:.92rem;cursor:pointer;transition:.15s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
        .btn-submit:hover{background:var(--pri-dk);}.btn-submit:active{transform:scale(.98);}
        .auth-footer{text-align:center;margin-top:1.25rem;font-size:.88rem;color:var(--t3);}
        .auth-footer a{color:var(--pri);font-weight:600;text-decoration:none;}.auth-footer a:hover{text-decoration:underline;}
        .success-banner{background:var(--pri-lt);border:1px solid #bfdbfe;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:var(--pri-dk);}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-top">
            <a href="{{ url('/home') }}"><img src="/img/logo.png" alt="Logo" style="height:28px;width:auto;"></a>
            <a href="{{ route('login') }}"><i class="bi bi-arrow-left"></i> Atpakaļ</a>
        </div>

        <div style="width:56px;height:56px;background:var(--pri-lt);border-radius:16px;display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:1.5rem;margin-bottom:1.25rem;">
            <i class="bi bi-key"></i>
        </div>

        <h1>Atjauno savu paroli</h1>
        <p class="sub">Ievadi e-pastu un mēs nosūtīsim atjaunošanas saiti.</p>

        @if(session('status'))
            <div class="success-banner"><i class="bi bi-check-circle me-1"></i> {{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#dc2626;">
                @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="f-group">
                <label class="f-label">E-pasts</label>
                <input type="email" name="email" class="f-input" value="{{ old('email') }}" autocomplete="email" autofocus required>
            </div>
            <button type="submit" class="btn-submit"><i class="bi bi-envelope"></i> Nosūtīt atjaunošanas saiti</button>
        </form>

        <div class="auth-footer">
            Atceries paroli? <a href="{{ route('login') }}">Pieslēdzies</a>
        </div>
    </div>
</body>
</html>
