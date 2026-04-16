<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jauna parole — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--dan:#EF4444;--bg:#F8FAFC;--dk:#0f172a;--t1:#1e293b;--t3:#64748b;--t4:#94a3b8;--bdr:#e2e8f0;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:#334155;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
        .auth-card{width:100%;max-width:420px;animation:fadeUp .4s ease;}
        .auth-card h1{font-size:1.5rem;font-weight:800;color:var(--dk);margin-bottom:.3rem;}
        .auth-card .sub{font-size:.88rem;color:var(--t3);margin-bottom:1.5rem;}
        .f-label{font-size:.85rem;font-weight:600;color:var(--t1);margin-bottom:.35rem;display:block;}
        .f-input{width:100%;height:48px;padding:0 1rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-size:.9rem;color:var(--t1);transition:.2s;outline:none;}
        .f-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
        .f-group{margin-bottom:1rem;}
        .pw-wrap{position:relative;}
        .pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--t4);cursor:pointer;font-size:1.1rem;}
        .pw-bar{height:4px;background:var(--bdr);border-radius:99px;margin-top:.4rem;overflow:hidden;}
        .pw-fill{height:100%;border-radius:99px;transition:width .3s,background .3s;width:0;}
        .pw-rules{display:flex;flex-wrap:wrap;gap:.3rem .7rem;margin-top:.4rem;}
        .pw-rule{font-size:.7rem;color:var(--t4);display:flex;align-items:center;gap:.2rem;}.pw-rule.met{color:var(--suc);}
        .btn-submit{width:100%;height:48px;background:var(--pri);color:#fff;border:none;border-radius:var(--r-xs);font-weight:600;font-size:.92rem;cursor:pointer;transition:.15s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
        .btn-submit:hover{background:var(--pri-dk);}.btn-submit:active{transform:scale(.98);}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
    </style>
</head>
<body>
    <div class="auth-card">
        <div style="text-align:center;margin-bottom:2rem;">
            <a href="{{ url('/home') }}" style="text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;">
                <i class="bi bi-megaphone-fill" style="color:var(--pri);font-size:1.1rem;"></i>
                <strong style="color:var(--dk);font-size:1.1rem;">{{ config('app.name') }}</strong>
            </a>
        </div>

        <div style="width:56px;height:56px;background:var(--pri-lt);border-radius:16px;display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:1.5rem;margin-bottom:1.25rem;">
            <i class="bi bi-shield-lock"></i>
        </div>

        <h1>Izveido jaunu paroli</h1>
        <p class="sub">Izvēlies drošu paroli savam kontam.</p>

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#dc2626;">
                @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="f-group">
                <label class="f-label">E-pasts</label>
                <input type="email" name="email" class="f-input" value="{{ request('email') ?? old('email') }}" required readonly style="background:#f8fafc;">
            </div>

            <div class="f-group">
                <label class="f-label">Jaunā parole</label>
                <div class="pw-wrap">
                    <input type="password" name="password" class="f-input" id="pw1" required oninput="checkStr(this.value)">
                    <button type="button" class="pw-toggle" onclick="tPw('pw1',this)"><i class="bi bi-eye"></i></button>
                </div>
                <div class="pw-bar"><div class="pw-fill" id="pwF"></div></div>
                <div class="pw-rules">
                    <span class="pw-rule" id="rL"><i class="bi bi-circle"></i> 8+ rakstzīmes</span>
                    <span class="pw-rule" id="rC"><i class="bi bi-circle"></i> Lielie un mazie</span>
                    <span class="pw-rule" id="rN"><i class="bi bi-circle"></i> Cipars</span>
                    <span class="pw-rule" id="rS"><i class="bi bi-circle"></i> Simbols</span>
                </div>
            </div>

            <div class="f-group">
                <label class="f-label">Apstiprini paroli</label>
                <div class="pw-wrap">
                    <input type="password" name="password_confirmation" class="f-input" id="pw2" required>
                    <button type="button" class="pw-toggle" onclick="tPw('pw2',this)"><i class="bi bi-eye"></i></button>
                </div>
            </div>

            <button type="submit" class="btn-submit"><i class="bi bi-check-lg"></i> Saglabāt jauno paroli</button>
        </form>
    </div>
    <script>
    function tPw(id,b){var i=document.getElementById(id);var p=i.type==='password';i.type=p?'text':'password';b.querySelector('i').className=p?'bi bi-eye-slash':'bi bi-eye';}
    function checkStr(v){var s=0,l=v.length>=8,c=/[a-z]/.test(v)&&/[A-Z]/.test(v),n=/\d/.test(v),y=/[^a-zA-Z0-9]/.test(v);if(l)s++;if(c)s++;if(n)s++;if(y)s++;var cl=['#EF4444','#F59E0B','#F59E0B','#10b981','#10b981'];var f=document.getElementById('pwF');f.style.width=(s*25)+'%';f.style.background=cl[s]||'#e2e8f0';sR('rL',l);sR('rC',c);sR('rN',n);sR('rS',y);}
    function sR(id,m){var e=document.getElementById(id);e.classList.toggle('met',m);e.querySelector('i').className=m?'bi bi-check-circle-fill':'bi bi-circle';}
    </script>
</body>
</html>
