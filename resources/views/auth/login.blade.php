<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pieslēgties — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--dan:#EF4444;--bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t2:#334155;--t3:#64748b;--t4:#94a3b8;--t5:#cbd5e1;--bdr:#e2e8f0;--r:16px;--r-sm:12px;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--t2);-webkit-font-smoothing:antialiased;min-height:100vh;}
        .auth-wrap{display:flex;min-height:100vh;}
        .auth-form-side{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem;}
        .auth-brand-side{width:40%;background:linear-gradient(135deg,#1E40AF 0%,#2563EB 50%,#3b82f6 100%);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem;color:#fff;position:relative;overflow:hidden;}
        .auth-brand-side::before{content:'';position:absolute;top:-100px;right:-80px;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.06);}
        .auth-brand-side::after{content:'';position:absolute;bottom:-80px;left:-60px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.04);}
        .brand-content{position:relative;z-index:2;text-align:center;max-width:360px;}
        .brand-logo{width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 1.5rem;backdrop-filter:blur(8px);}
        .brand-title{font-size:1.75rem;font-weight:800;margin-bottom:.5rem;}
        .brand-sub{font-size:.95rem;opacity:.75;margin-bottom:2rem;line-height:1.5;}
        .brand-stats{display:flex;gap:1.5rem;justify-content:center;}
        .brand-stat{text-align:center;}
        .brand-stat-val{font-size:1.25rem;font-weight:800;}
        .brand-stat-label{font-size:.7rem;opacity:.6;text-transform:uppercase;letter-spacing:.5px;}
        .auth-top{width:100%;max-width:420px;display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;}
        .auth-top a{font-size:.85rem;color:var(--t3);text-decoration:none;display:flex;align-items:center;gap:.3rem;transition:.15s;}
        .auth-top a:hover{color:var(--pri);}
        .auth-card{width:100%;max-width:420px;}
        .auth-card h1{font-size:1.6rem;font-weight:800;color:var(--dk);margin-bottom:.3rem;}
        .auth-card .sub{font-size:.9rem;color:var(--t3);margin-bottom:1.75rem;}
        .social-btn{width:100%;height:48px;border:1.5px solid var(--bdr);border-radius:var(--r-xs);background:var(--wh);display:flex;align-items:center;justify-content:center;gap:.6rem;font-size:.88rem;font-weight:500;color:var(--t1);cursor:pointer;transition:.15s;text-decoration:none;margin-bottom:.5rem;}
        .social-btn:hover{border-color:var(--t5);box-shadow:0 2px 8px rgba(0,0,0,.05);transform:translateY(-1px);color:var(--t1);}
        .social-btn img,.social-btn svg{width:20px;height:20px;}
        .social-btn-apple{background:var(--dk);color:#fff;border-color:var(--dk);}
        .social-btn-apple:hover{background:#1a1a2e;color:#fff;}
        .divider{display:flex;align-items:center;gap:.75rem;margin:1.25rem 0;color:var(--t4);font-size:.8rem;}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--bdr);}
        .f-label{font-size:.85rem;font-weight:600;color:var(--t1);margin-bottom:.35rem;display:flex;justify-content:space-between;align-items:center;}
        .f-input{width:100%;height:48px;padding:0 1rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-size:.9rem;color:var(--t1);transition:.2s;background:var(--wh);outline:none;}
        .f-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
        .f-input.is-err{border-color:var(--dan);}
        .f-group{margin-bottom:1rem;}
        .f-err{font-size:.75rem;color:var(--dan);margin-top:.3rem;display:flex;align-items:center;gap:.3rem;}
        .pw-wrap{position:relative;}
        .pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--t4);cursor:pointer;padding:4px;font-size:1.1rem;}
        .pw-toggle:hover{color:var(--t2);}
        .check-row{display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;}
        .check-row input[type=checkbox]{width:18px;height:18px;border-radius:4px;accent-color:var(--pri);}
        .check-row label{font-size:.85rem;color:var(--t2);cursor:pointer;}
        .btn-submit{width:100%;height:48px;background:var(--pri);color:#fff;border:none;border-radius:var(--r-xs);font-weight:600;font-size:.92rem;cursor:pointer;transition:.15s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
        .btn-submit:hover{background:var(--pri-dk);}
        .btn-submit:active{transform:scale(.98);}
        .btn-submit:disabled{opacity:.6;cursor:not-allowed;}
        .auth-footer{text-align:center;margin-top:1.5rem;font-size:.88rem;color:var(--t3);}
        .auth-footer a{color:var(--pri);font-weight:600;text-decoration:none;}
        .auth-footer a:hover{text-decoration:underline;}
        .error-banner{background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:#dc2626;animation:shake .4s;}
        @keyframes shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-4px)}40%,80%{transform:translateX(4px)}}
        .fade-in{animation:fadeUp .4s ease;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
        @media(max-width:991.98px){.auth-brand-side{display:none;}}
        @media(max-width:575.98px){.auth-form-side{padding:1.5rem;}.auth-card{max-width:100%;}}
    </style>
</head>
<body>
    <div class="auth-wrap">
        {{-- Form Side --}}
        <div class="auth-form-side">
            <div class="auth-top">
                <a href="{{ url('/home') }}"><i class="bi bi-megaphone-fill" style="color:var(--pri);"></i> <strong style="color:var(--dk);">{{ config('app.name') }}</strong></a>
                <a href="{{ url('/home') }}"><i class="bi bi-arrow-left"></i> Uz sākumu</a>
            </div>

            <div class="auth-card fade-in">
                <h1>Laipni lūdzam atpakaļ!</h1>
                <p class="sub">Pieslēdzies, lai turpinātu darījumus</p>

                {{-- Error --}}
                @if($errors->any())
                    <div class="error-banner">
                        <i class="bi bi-exclamation-circle"></i> Nepareizs e-pasts vai parole.
                    </div>
                @endif

                @if(session('status'))
                    <div style="background:var(--pri-lt);border:1px solid #bfdbfe;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:var(--pri-dk);">
                        <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
                    </div>
                @endif

                {{-- Social --}}
                <a href="#" class="social-btn">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Turpināt ar Google
                </a>
                <a href="#" class="social-btn social-btn-apple">
                    <i class="bi bi-apple" style="font-size:1.2rem;"></i>
                    Turpināt ar Apple
                </a>

                <div class="divider">vai pieslēdzies ar e-pastu</div>

                {{-- Form --}}
                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="f-group">
                        <label class="f-label">E-pasts</label>
                        <input type="email" name="email" class="f-input @error('email') is-err @enderror" value="{{ old('email') }}" autocomplete="email" autofocus required>
                    </div>

                    <div class="f-group">
                        <label class="f-label">
                            Parole
                            <a href="{{ route('password.request') }}" style="font-size:.8rem;font-weight:500;color:var(--pri);text-decoration:none;">Aizmirsi paroli?</a>
                        </label>
                        <div class="pw-wrap">
                            <input type="password" name="password" class="f-input" id="loginPw" autocomplete="current-password" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('loginPw',this)" aria-label="Rādīt paroli">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="check-row">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Atceries mani 30 dienas</label>
                    </div>

                    <button type="submit" class="btn-submit" id="loginBtn">
                        <i class="bi bi-box-arrow-in-right"></i> Pieslēgties
                    </button>
                </form>

                <div class="auth-footer">
                    Nav konta? <a href="{{ route('register') }}">Reģistrējies</a>
                </div>
            </div>
        </div>

        {{-- Brand Side --}}
        <div class="auth-brand-side">
            <div class="brand-content">
                <div class="brand-logo"><i class="bi bi-megaphone-fill"></i></div>
                <div class="brand-title">Tirgus tavā pilsētā</div>
                <div class="brand-sub">Pērc un pārdod uzticamiem cilvēkiem visā Latvijā — ātri, vienkārši un droši.</div>
                <div class="brand-stats">
                    <div class="brand-stat">
                        <div class="brand-stat-val">{{ \App\Models\Advertisement::where('published',1)->count() }}+</div>
                        <div class="brand-stat-label">Sludinājumi</div>
                    </div>
                    <div class="brand-stat">
                        <div class="brand-stat-val">{{ \App\Models\User::count() }}+</div>
                        <div class="brand-stat-label">Lietotāji</div>
                    </div>
                    <div class="brand-stat">
                        <div class="brand-stat-val">{{ \App\Models\Category::count() }}</div>
                        <div class="brand-stat-label">Kategorijas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePw(id, btn) {
        var inp = document.getElementById(id);
        var isPass = inp.type === 'password';
        inp.type = isPass ? 'text' : 'password';
        btn.querySelector('i').className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
    }
    document.getElementById('loginForm').addEventListener('submit', function() {
        var btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Pieslēdzas...';
    });
    </script>
</body>
</html>
