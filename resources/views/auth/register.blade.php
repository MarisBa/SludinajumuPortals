<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reģistrēties — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--dan:#EF4444;--bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t2:#334155;--t3:#64748b;--t4:#94a3b8;--t5:#cbd5e1;--bdr:#e2e8f0;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--t2);-webkit-font-smoothing:antialiased;min-height:100vh;}
        .auth-wrap{display:flex;min-height:100vh;}
        .auth-form-side{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem;}
        .auth-brand-side{width:40%;background:linear-gradient(135deg,#1E40AF 0%,#2563EB 50%,#3b82f6 100%);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem;color:#fff;position:relative;overflow:hidden;}
        .auth-brand-side::before{content:'';position:absolute;top:-100px;right:-80px;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.06);}
        .brand-content{position:relative;z-index:2;text-align:center;max-width:360px;}
        .brand-logo{width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 1.5rem;backdrop-filter:blur(8px);}
        .brand-title{font-size:1.75rem;font-weight:800;margin-bottom:.5rem;}
        .brand-sub{font-size:.95rem;opacity:.75;line-height:1.5;}
        .auth-top{width:100%;max-width:440px;display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;}
        .auth-top a{font-size:.85rem;color:var(--t3);text-decoration:none;display:flex;align-items:center;gap:.3rem;transition:.15s;}
        .auth-top a:hover{color:var(--pri);}
        .auth-card{width:100%;max-width:440px;}
        .auth-card h1{font-size:1.5rem;font-weight:800;color:var(--dk);margin-bottom:.3rem;}
        .auth-card .sub{font-size:.88rem;color:var(--t3);margin-bottom:.75rem;}
        .benefits{display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .benefit{font-size:.78rem;color:var(--suc);font-weight:500;display:flex;align-items:center;gap:.25rem;}
        .social-btn{width:100%;height:48px;border:1.5px solid var(--bdr);border-radius:var(--r-xs);background:var(--wh);display:flex;align-items:center;justify-content:center;gap:.6rem;font-size:.88rem;font-weight:500;color:var(--t1);cursor:pointer;transition:.15s;text-decoration:none;margin-bottom:.5rem;}
        .social-btn:hover{border-color:var(--t5);box-shadow:0 2px 8px rgba(0,0,0,.05);transform:translateY(-1px);color:var(--t1);}
        .social-btn img,.social-btn svg{width:20px;height:20px;}
        .social-btn-apple{background:var(--dk);color:#fff;border-color:var(--dk);}.social-btn-apple:hover{background:#1a1a2e;color:#fff;}
        .divider{display:flex;align-items:center;gap:.75rem;margin:1.25rem 0;color:var(--t4);font-size:.8rem;}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--bdr);}
        .f-label{font-size:.85rem;font-weight:600;color:var(--t1);margin-bottom:.35rem;display:block;}
        .f-input{width:100%;height:48px;padding:0 1rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-size:.9rem;color:var(--t1);transition:.2s;background:var(--wh);outline:none;}
        .f-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
        .f-input.is-err{border-color:var(--dan);}
        .f-group{margin-bottom:.85rem;}
        .f-err{font-size:.73rem;color:var(--dan);margin-top:.25rem;}
        .pw-wrap{position:relative;}
        .pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--t4);cursor:pointer;font-size:1.1rem;}.pw-toggle:hover{color:var(--t2);}
        .pw-bar{height:4px;background:var(--bdr);border-radius:99px;margin-top:.4rem;overflow:hidden;}
        .pw-fill{height:100%;border-radius:99px;transition:width .3s,background .3s;width:0;}
        .pw-rules{display:flex;flex-wrap:wrap;gap:.3rem .7rem;margin-top:.4rem;}
        .pw-rule{font-size:.7rem;color:var(--t4);display:flex;align-items:center;gap:.2rem;}.pw-rule.met{color:var(--suc);}
        .check-row{display:flex;align-items:flex-start;gap:.5rem;margin-bottom:.75rem;}
        .check-row input[type=checkbox]{width:18px;height:18px;border-radius:4px;accent-color:var(--pri);margin-top:2px;flex-shrink:0;}
        .check-row label{font-size:.82rem;color:var(--t2);cursor:pointer;line-height:1.4;}
        .check-row label a{color:var(--pri);text-decoration:none;}.check-row label a:hover{text-decoration:underline;}
        .btn-submit{width:100%;height:48px;background:var(--pri);color:#fff;border:none;border-radius:var(--r-xs);font-weight:600;font-size:.92rem;cursor:pointer;transition:.15s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
        .btn-submit:hover{background:var(--pri-dk);}.btn-submit:active{transform:scale(.98);}.btn-submit:disabled{opacity:.5;cursor:not-allowed;}
        .auth-footer{text-align:center;margin-top:1.25rem;font-size:.88rem;color:var(--t3);}
        .auth-footer a{color:var(--pri);font-weight:600;text-decoration:none;}.auth-footer a:hover{text-decoration:underline;}
        .error-banner{background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#dc2626;}
        .fade-in{animation:fadeUp .4s ease;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
        @media(max-width:991.98px){.auth-brand-side{display:none;}}
        @media(max-width:575.98px){.auth-form-side{padding:1.5rem;}.auth-card{max-width:100%;}}
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-form-side">
            <div class="auth-top">
                <a href="{{ url('/home') }}"><i class="bi bi-megaphone-fill" style="color:var(--pri);"></i> <strong style="color:var(--dk);">{{ config('app.name') }}</strong></a>
                <a href="{{ url('/home') }}"><i class="bi bi-arrow-left"></i> Uz sākumu</a>
            </div>

            <div class="auth-card fade-in">
                <h1>Izveido bezmaksas kontu</h1>
                <p class="sub">30 sekundēs. Nav slēptu maksājumu. Sāc pārdot uzreiz.</p>

                <div class="benefits">
                    <span class="benefit"><i class="bi bi-check-circle-fill"></i> Bezmaksas sludinājumi</span>
                    <span class="benefit"><i class="bi bi-check-circle-fill"></i> Verificēti lietotāji</span>
                    <span class="benefit"><i class="bi bi-check-circle-fill"></i> Droši darījumi</span>
                </div>

                @if($errors->any())
                    <div class="error-banner">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
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

                <div class="divider">vai reģistrējies ar e-pastu</div>

                <form action="{{ route('register') }}" method="POST" id="regForm">
                    @csrf

                    <div class="f-group">
                        <label class="f-label">Vārds, uzvārds</label>
                        <input type="text" name="name" class="f-input @error('name') is-err @enderror" value="{{ old('name') }}" placeholder="Jānis Bērziņš" autocomplete="name" required>
                        @error('name')<div class="f-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="f-group">
                        <label class="f-label">E-pasts</label>
                        <input type="email" name="email" class="f-input @error('email') is-err @enderror" value="{{ old('email') }}" autocomplete="email" required>
                        @error('email')<div class="f-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="f-group">
                        <label class="f-label">Parole</label>
                        <div class="pw-wrap">
                            <input type="password" name="password" class="f-input @error('password') is-err @enderror" id="regPw" autocomplete="new-password" required oninput="checkStrength(this.value)">
                            <button type="button" class="pw-toggle" onclick="togglePw('regPw',this)" aria-label="Rādīt paroli"><i class="bi bi-eye"></i></button>
                        </div>
                        <div class="pw-bar"><div class="pw-fill" id="pwFill"></div></div>
                        <div class="pw-rules">
                            <span class="pw-rule" id="rLen"><i class="bi bi-circle"></i> 8+ rakstzīmes</span>
                            <span class="pw-rule" id="rCase"><i class="bi bi-circle"></i> Lielie un mazie</span>
                            <span class="pw-rule" id="rNum"><i class="bi bi-circle"></i> Cipars</span>
                            <span class="pw-rule" id="rSym"><i class="bi bi-circle"></i> Simbols</span>
                        </div>
                        @error('password')<div class="f-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="f-group">
                        <label class="f-label">Apstiprini paroli</label>
                        <div class="pw-wrap">
                            <input type="password" name="password_confirmation" class="f-input" id="regPwC" autocomplete="new-password" required oninput="checkMatch()">
                            <button type="button" class="pw-toggle" onclick="togglePw('regPwC',this)" aria-label="Rādīt paroli"><i class="bi bi-eye"></i></button>
                        </div>
                        <div id="matchMsg" style="font-size:.73rem;margin-top:.25rem;"></div>
                    </div>

                    <div class="check-row">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">Piekrītu <a href="#" target="_blank">Lietošanas noteikumiem</a> un <a href="#" target="_blank">Privātuma politikai</a></label>
                    </div>

                    <div class="check-row">
                        <input type="checkbox" name="newsletter" id="newsletter">
                        <label for="newsletter">Vēlos saņemt jaunumus un piedāvājumus e-pastā</label>
                    </div>

                    <button type="submit" class="btn-submit" id="regBtn">
                        <i class="bi bi-person-plus"></i> Izveidot kontu
                    </button>
                </form>

                <div class="auth-footer">
                    Jau ir konts? <a href="{{ route('login') }}">Pieslēdzies</a>
                </div>
            </div>
        </div>

        <div class="auth-brand-side">
            <div class="brand-content">
                <div class="brand-logo"><i class="bi bi-megaphone-fill"></i></div>
                <div class="brand-title">Pievienojies kopienai</div>
                <div class="brand-sub">Tūkstošiem cilvēku Latvijā jau pērk un pārdod katru dienu. Pievienojies viņiem!</div>
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
    function checkStrength(val) {
        var s = 0;
        var len = val.length >= 8; if (len) s++;
        var cas = /[a-z]/.test(val) && /[A-Z]/.test(val); if (cas) s++;
        var num = /\d/.test(val); if (num) s++;
        var sym = /[^a-zA-Z0-9]/.test(val); if (sym) s++;
        var colors = ['#EF4444','#F59E0B','#F59E0B','#10b981','#10b981'];
        var fill = document.getElementById('pwFill');
        fill.style.width = (s * 25) + '%';
        fill.style.background = colors[s] || '#e2e8f0';
        setR('rLen', len); setR('rCase', cas); setR('rNum', num); setR('rSym', sym);
    }
    function setR(id, met) {
        var el = document.getElementById(id);
        el.classList.toggle('met', met);
        el.querySelector('i').className = met ? 'bi bi-check-circle-fill' : 'bi bi-circle';
    }
    function checkMatch() {
        var pw = document.getElementById('regPw').value;
        var c = document.getElementById('regPwC').value;
        var msg = document.getElementById('matchMsg');
        if (!c) { msg.textContent = ''; return; }
        msg.innerHTML = pw === c
            ? '<span style="color:var(--suc);"><i class="bi bi-check-circle-fill"></i> Paroles sakrīt</span>'
            : '<span style="color:var(--dan);"><i class="bi bi-x-circle"></i> Paroles nesakrīt</span>';
    }
    document.getElementById('regForm').addEventListener('submit', function() {
        var btn = document.getElementById('regBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Izveido kontu...';
    });
    </script>
</body>
</html>
