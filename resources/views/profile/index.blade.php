<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iestatījumi — {{ config('app.name', 'SludinajumuPortals') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--suc-lt:#dcfce7;--warn:#F59E0B;--warn-lt:#fef3c7;--dan:#EF4444;--bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t2:#334155;--t3:#64748b;--t4:#94a3b8;--t5:#cbd5e1;--bdr:#e2e8f0;--r:16px;--r-sm:12px;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--t2);-webkit-font-smoothing:antialiased;}

        /* Sidebar */
        .dash-sidebar{background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r);padding:1.25rem;position:sticky;top:80px;}
        .dash-avatar{width:56px;height:56px;border-radius:50%;background:var(--pri-lt);display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:1.5rem;}
        .dash-nav{display:flex;flex-direction:column;gap:.2rem;margin-top:.75rem;}
        .dash-nav-item{display:flex;align-items:center;gap:.6rem;padding:.55rem .75rem;border-radius:var(--r-xs);font-size:.85rem;font-weight:500;color:var(--t2);text-decoration:none;transition:.15s;border-left:3px solid transparent;}
        .dash-nav-item:hover{background:var(--pri-lt);color:var(--pri);}
        .dash-nav-item.active{background:var(--pri-lt);color:var(--pri);border-left-color:var(--pri);font-weight:600;}
        .dash-nav-item i{font-size:1rem;width:20px;text-align:center;}

        /* Tabs */
        .prof-tabs{display:flex;gap:0;border-bottom:2px solid var(--bdr);margin-bottom:1.5rem;overflow-x:auto;-webkit-overflow-scrolling:touch;}
        .prof-tab{padding:.75rem 1.25rem;font-size:.88rem;font-weight:600;color:var(--t3);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:.2s;white-space:nowrap;}
        .prof-tab:hover{color:var(--pri);}
        .prof-tab.active{color:var(--pri);border-bottom-color:var(--pri);}

        /* Cards */
        .s-card{background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r);padding:1.75rem;margin-bottom:1.25rem;}
        .s-card-title{font-size:1.05rem;font-weight:700;color:var(--dk);display:flex;align-items:center;gap:.45rem;margin-bottom:.25rem;}
        .s-card-title i{color:var(--pri);font-size:1.1rem;}
        .s-card-sub{font-size:.82rem;color:var(--t4);margin-bottom:1.25rem;}

        /* Inputs */
        .f-label{font-size:.85rem;font-weight:600;color:var(--t1);margin-bottom:.35rem;display:block;}
        .f-label .req{color:var(--dan);}
        .f-input{width:100%;height:44px;padding:0 1rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-size:.88rem;color:var(--t1);transition:.2s;background:var(--wh);outline:none;}
        .f-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
        .f-input.is-err{border-color:var(--dan);}
        .f-textarea{height:auto;min-height:80px;padding:.75rem 1rem;resize:vertical;}
        .f-help{font-size:.73rem;color:var(--t4);margin-top:.3rem;}
        .f-err{font-size:.73rem;color:var(--dan);margin-top:.3rem;}
        .f-group{margin-bottom:1rem;}

        /* Buttons */
        .btn-s{height:44px;padding:0 1.5rem;border-radius:var(--r-xs);font-weight:600;font-size:.88rem;display:inline-flex;align-items:center;justify-content:center;gap:.4rem;border:none;cursor:pointer;transition:.15s;}
        .btn-s:active{transform:scale(.98);}
        .btn-pri{background:var(--pri);color:#fff;}.btn-pri:hover{background:var(--pri-dk);color:#fff;}
        .btn-out{background:none;border:1.5px solid var(--bdr);color:var(--t2);}.btn-out:hover{border-color:var(--pri);color:var(--pri);}
        .btn-dan{background:none;border:1.5px solid var(--dan);color:var(--dan);}.btn-dan:hover{background:#fef2f2;}
        .btn-suc{background:var(--suc);color:#fff;}.btn-suc:hover{background:#059669;color:#fff;}

        /* Avatar */
        .av-wrap{position:relative;width:96px;height:96px;margin-bottom:1rem;}
        .av-img{width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--bdr);}
        .av-placeholder{width:96px;height:96px;border-radius:50%;background:var(--pri-lt);display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:2.5rem;border:3px solid var(--bdr);}
        .av-overlay{position:absolute;inset:0;border-radius:50%;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.78rem;font-weight:600;opacity:0;transition:.2s;cursor:pointer;}
        .av-wrap:hover .av-overlay{opacity:1;}

        /* Toggle */
        .toggle-wrap{display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid var(--bdr);}
        .toggle-wrap:last-child{border:none;}
        .toggle-label{font-size:.88rem;font-weight:500;color:var(--t1);}
        .toggle-desc{font-size:.75rem;color:var(--t4);}
        .toggle-switch{width:44px;height:24px;border-radius:99px;background:var(--t5);position:relative;cursor:pointer;transition:.2s;flex-shrink:0;}
        .toggle-switch.on{background:var(--pri);}
        .toggle-switch .toggle-thumb{width:20px;height:20px;border-radius:50%;background:#fff;position:absolute;top:2px;left:2px;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.15);}
        .toggle-switch.on .toggle-thumb{left:22px;}

        /* Verify row */
        .verify-row{display:flex;align-items:center;justify-content:space-between;padding:.85rem 0;border-bottom:1px solid var(--bdr);}
        .verify-row:last-child{border:none;}
        .verify-info{display:flex;align-items:center;gap:.75rem;}
        .verify-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
        .verify-val{font-size:.9rem;font-weight:500;color:var(--t1);}
        .verify-sub{font-size:.72rem;color:var(--t4);}
        .pill-ok{display:inline-flex;align-items:center;gap:.25rem;background:var(--suc-lt);color:#16a34a;font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:99px;}
        .pill-warn{display:inline-flex;align-items:center;gap:.25rem;background:var(--warn-lt);color:#d97706;font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:99px;}

        /* Password strength */
        .pw-bar{height:4px;background:var(--bdr);border-radius:99px;margin-top:.4rem;overflow:hidden;}
        .pw-fill{height:100%;border-radius:99px;transition:width .3s,background .3s;}
        .pw-rules{display:flex;flex-wrap:wrap;gap:.4rem .75rem;margin-top:.5rem;}
        .pw-rule{font-size:.72rem;color:var(--t4);display:flex;align-items:center;gap:.25rem;}
        .pw-rule.met{color:var(--suc);}

        /* Check list */
        .v-check{display:flex;align-items:center;gap:.75rem;padding:.75rem 0;border-bottom:1px solid var(--bdr);}
        .v-check:last-child{border:none;}
        .v-check-icon{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0;}
        .v-done{background:var(--suc-lt);color:#16a34a;}
        .v-todo{background:#f1f5f9;color:var(--t4);}
        .v-check-text{flex:1;font-size:.88rem;color:var(--t1);}
        .v-progress-ring{width:80px;height:80px;margin:0 auto 1rem;}

        /* Danger zone */
        .danger-card{background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r);padding:1.5rem;}

        /* Tab panels */
        .tab-panel{display:none;animation:tabIn .25s ease;}
        .tab-panel.active{display:block;}
        @keyframes tabIn{from{opacity:0;transform:translateY(8px);}to{opacity:1;transform:translateY(0);}}

        /* Toast */
        .toast-wrap{position:fixed;top:20px;right:20px;z-index:3000;}
        .toast-msg{background:var(--t1);color:#fff;padding:.65rem 1.1rem;border-radius:var(--r-xs);font-size:.85rem;font-weight:500;box-shadow:0 12px 32px rgba(0,0,0,.1);animation:toastIn .3s ease,toastOut .3s ease 2.7s forwards;}
        @keyframes toastIn{from{opacity:0;transform:translateX(30px);}to{opacity:1;transform:translateX(0);}}
        @keyframes toastOut{from{opacity:1;}to{opacity:0;}}

        .sp-foot{background:var(--dk);color:rgba(255,255,255,.6);padding:2rem 0 1rem;margin-top:3rem;}
        .sp-foot a{color:rgba(255,255,255,.5);text-decoration:none;font-size:.83rem;}

        @media(max-width:991.98px){.dash-sidebar-col{display:none;}}
        @media(max-width:767.98px){.s-card{padding:1.25rem;}.prof-tabs{gap:0;}}
    </style>
</head>
<body>
    <div class="toast-wrap" id="toastWrap"></div>

    @include('partials.navbar')

    <div class="container" style="margin-top:1.5rem;margin-bottom:2rem;">
        <div class="row g-4">
            {{-- Sidebar --}}
            <div class="col-lg-3 dash-sidebar-col">
                <div class="dash-sidebar">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="dash-avatar"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div style="font-size:1rem;font-weight:700;color:var(--dk);">{{ auth()->user()->name }}</div>
                            <div style="font-size:.75rem;color:var(--t4);">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="dash-nav">
                        <a href="{{ route('ads.index') }}" class="dash-nav-item"><i class="bi bi-collection"></i> Mani sludinājumi</a>
                        <a href="{{ url('/ads/create') }}" class="dash-nav-item"><i class="bi bi-plus-circle"></i> Jauns sludinājums</a>
                        <a href="{{ route('profile') }}" class="dash-nav-item active"><i class="bi bi-gear"></i> Iestatījumi</a>
                        <a href="{{ url('/home') }}" class="dash-nav-item"><i class="bi bi-house-door"></i> Sākumlapa</a>
                    </div>
                </div>
            </div>

            {{-- Main --}}
            <div class="col-lg-9">
                {{-- Success --}}
                @if(session('message'))
                    <div style="background:var(--suc-lt);border:1px solid #a7f3d0;border-radius:var(--r-sm);padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
                        <i class="bi bi-check-circle-fill" style="color:var(--suc);"></i>
                        <span style="font-size:.88rem;color:#065f46;">{{ session('message') }}</span>
                    </div>
                @endif

                <div style="margin-bottom:1.5rem;">
                    <h1 style="font-size:1.4rem;font-weight:800;color:var(--dk);">Iestatījumi</h1>
                    <p style="font-size:.88rem;color:var(--t3);margin-top:.2rem;">Pārvaldi savu kontu, drošību un privātumu</p>
                </div>

                {{-- Tabs --}}
                <div class="prof-tabs">
                    <div class="prof-tab active" onclick="switchTab(0,this)"><i class="bi bi-person me-1"></i> Profils</div>
                    <div class="prof-tab" onclick="switchTab(1,this)"><i class="bi bi-shield-lock me-1"></i> Drošība</div>
                    <div class="prof-tab" onclick="switchTab(2,this)"><i class="bi bi-eye me-1"></i> Privātums</div>
                </div>

                {{-- ===== TAB 0: Profile ===== --}}
                <div class="tab-panel active" id="tab0">
                    <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="s-card">
                            <div class="s-card-title"><i class="bi bi-person-circle"></i> Publiskais profils</div>
                            <div class="s-card-sub">Šo informāciju redzēs citi lietotāji</div>

                            <div class="d-flex gap-4 flex-wrap">
                                {{-- Avatar --}}
                                <div>
                                    <div class="av-wrap" onclick="document.getElementById('avatarInput').click()">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ Storage::url(auth()->user()->avatar) }}" class="av-img" id="avatarPreview">
                                        @else
                                            <div class="av-placeholder" id="avatarPreview"><i class="bi bi-person-fill"></i></div>
                                        @endif
                                        <div class="av-overlay"><i class="bi bi-camera me-1"></i> Mainīt</div>
                                    </div>
                                    <input type="file" name="image" id="avatarInput" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                                </div>

                                {{-- Fields --}}
                                <div style="flex:1;min-width:250px;">
                                    <div class="f-group">
                                        <label class="f-label">Vārds <span class="req">*</span></label>
                                        <input type="text" name="name" class="f-input" value="{{ auth()->user()->name }}" required>
                                        @error('name')<div class="f-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                                    </div>
                                    <div class="f-group">
                                        <label class="f-label">Adrese</label>
                                        <input type="text" name="address" class="f-input" value="{{ auth()->user()->address }}" placeholder="piem. Rīga, Latvija">
                                    </div>
                                    <div class="f-group">
                                        <label class="f-label">Par mani <span style="font-weight:400;color:var(--t4);">(neobligāts)</span></label>
                                        <textarea class="f-input f-textarea" name="bio" maxlength="160" placeholder="Īss apraksts par sevi...">{{ auth()->user()->bio ?? '' }}</textarea>
                                        <div class="f-help">Līdz 160 rakstzīmēm</div>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top:1rem;">
                                <button type="submit" class="btn-s btn-pri" id="profileSaveBtn">
                                    <i class="bi bi-check-lg"></i> Saglabāt izmaiņas
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Contact info --}}
                    <div class="s-card">
                        <div class="s-card-title"><i class="bi bi-shield-check"></i> Kontaktinformācija</div>
                        <div class="s-card-sub">Verificēts kontakts palielina uzticamību</div>

                        <div class="verify-row">
                            <div class="verify-info">
                                <div class="verify-icon" style="background:var(--pri-lt);color:var(--pri);"><i class="bi bi-envelope-fill"></i></div>
                                <div>
                                    <div class="verify-val">{{ auth()->user()->email }}</div>
                                    <div class="verify-sub">E-pasta adrese</div>
                                </div>
                            </div>
                            @if(auth()->user()->email_verified_at)
                                <span class="pill-ok"><i class="bi bi-check-circle-fill"></i> Verificēts</span>
                            @else
                                <span class="pill-warn"><i class="bi bi-exclamation-circle"></i> Nav verificēts</span>
                            @endif
                        </div>

                        <div class="verify-row">
                            <div class="verify-info">
                                <div class="verify-icon" style="background:var(--suc-lt);color:var(--suc);"><i class="bi bi-telephone-fill"></i></div>
                                <div>
                                    <div class="verify-val">{{ auth()->user()->phone ?? 'Nav pievienots' }}</div>
                                    <div class="verify-sub">Tālrunis</div>
                                </div>
                            </div>
                            @if(auth()->user()->phone)
                                <span class="pill-ok"><i class="bi bi-check-circle-fill"></i> Verificēts</span>
                            @else
                                <button class="btn-s btn-out" style="height:34px;padding:0 .85rem;font-size:.8rem;">Pievienot</button>
                            @endif
                        </div>

                        <div style="margin-top:.75rem;padding:.75rem;background:#f0fdf4;border-radius:var(--r-xs);font-size:.78rem;color:#166534;">
                            <i class="bi bi-stars me-1"></i> Verificēts tālrunis = 2× vairāk atbildes no pircējiem
                        </div>
                    </div>
                </div>

                {{-- ===== TAB 1: Security ===== --}}
                <div class="tab-panel" id="tab1">
                    <form action="{{ route('account.password.update') }}" method="POST">
                        @csrf
                        <div class="s-card">
                            <div class="s-card-title"><i class="bi bi-key"></i> Parole</div>
                            <div class="s-card-sub">Regulāri mainiet paroli drošībai</div>

                            <div class="f-group">
                                <label class="f-label">Pašreizējā parole <span class="req">*</span></label>
                                <div style="position:relative;">
                                    <input type="password" name="current_password" class="f-input" id="pwCurrent">
                                    <button type="button" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--t4);cursor:pointer;" onclick="togglePw('pwCurrent',this)" aria-label="Rādīt paroli">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')<div class="f-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="f-group">
                                <label class="f-label">Jaunā parole <span class="req">*</span></label>
                                <div style="position:relative;">
                                    <input type="password" name="password" class="f-input" id="pwNew" oninput="checkStrength(this.value)">
                                    <button type="button" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--t4);cursor:pointer;" onclick="togglePw('pwNew',this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="pw-bar"><div class="pw-fill" id="pwFill" style="width:0;"></div></div>
                                <div class="pw-rules">
                                    <span class="pw-rule" id="pwLen"><i class="bi bi-circle"></i> 8+ rakstzīmes</span>
                                    <span class="pw-rule" id="pwCase"><i class="bi bi-circle"></i> Lielie un mazie</span>
                                    <span class="pw-rule" id="pwNum"><i class="bi bi-circle"></i> Cipars</span>
                                    <span class="pw-rule" id="pwSym"><i class="bi bi-circle"></i> Simbols</span>
                                </div>
                                @error('password')<div class="f-err"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="f-group">
                                <label class="f-label">Apstiprināt paroli <span class="req">*</span></label>
                                <input type="password" name="password_confirmation" class="f-input" id="pwConfirm" oninput="checkMatch()">
                                <div class="f-help" id="pwMatchMsg"></div>
                            </div>

                            <button type="submit" class="btn-s btn-pri"><i class="bi bi-check-lg"></i> Atjaunot paroli</button>
                        </div>
                    </form>

                    {{-- Danger Zone --}}
                    <div class="danger-card">
                        <div style="font-size:1rem;font-weight:700;color:#dc2626;margin-bottom:.25rem;"><i class="bi bi-exclamation-triangle me-1"></i> Bīstamā zona</div>
                        <p style="font-size:.85rem;color:#991b1b;margin-bottom:.75rem;">Konta dzēšana ir neatgriezeniska. Visi tavi sludinājumi, ziņas un dati tiks dzēsti.</p>
                        <button type="button" class="btn-s btn-dan" style="height:38px;font-size:.85rem;" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash3"></i> Dzēst kontu
                        </button>
                    </div>

                    {{-- Account deletion modal --}}
                    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                        Apstiprini konta dzēšanu
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
                                </div>
                                <form method="POST" action="{{ route('account.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <strong>Uzmanību!</strong> Šī darbība ir neatgriezeniska.
                                            Visi tavi sludinājumi, ziņas un dati tiks dzēsti.
                                        </div>
                                        <label class="form-label">Lai apstiprinātu, ievadi savu paroli:</label>
                                        <input type="password" name="password" class="form-control" required
                                               placeholder="Pašreizējā parole" autocomplete="current-password">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atcelt</button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Dzēst kontu uz visiem laikiem
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== TAB 2: Privacy ===== --}}
                <div class="tab-panel" id="tab2">
                    @php
                        $currentVisibility = auth()->user()->privacy_prefs['profile_visibility'] ?? 'public';
                    @endphp

                    <form action="{{ route('account.privacy.update') }}" method="POST">
                        @csrf
                        <div class="s-card">
                            <div class="s-card-title"><i class="bi bi-eye"></i> Profila redzamība</div>
                            <div class="s-card-sub">Kas var redzēt tavu profilu</div>
                            <div class="d-flex flex-column gap-2">
                                <label style="display:flex;align-items:center;gap:.6rem;padding:.6rem .75rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);cursor:pointer;transition:.15s;" class="priv-radio">
                                    <input type="radio" name="profile_visibility" value="public" {{ $currentVisibility === 'public' ? 'checked' : '' }} style="accent-color:var(--pri);">
                                    <div><div style="font-size:.88rem;font-weight:500;color:var(--t1);">Publisks</div><div style="font-size:.72rem;color:var(--t4);">Jebkurš apmeklētājs var redzēt</div></div>
                                </label>
                                <label style="display:flex;align-items:center;gap:.6rem;padding:.6rem .75rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);cursor:pointer;" class="priv-radio">
                                    <input type="radio" name="profile_visibility" value="registered" {{ $currentVisibility === 'registered' ? 'checked' : '' }} style="accent-color:var(--pri);">
                                    <div><div style="font-size:.88rem;font-weight:500;color:var(--t1);">Tikai reģistrēti lietotāji</div><div style="font-size:.72rem;color:var(--t4);">Redzams tikai ielogotiem</div></div>
                                </label>
                            </div>
                            <div style="margin-top:1rem;">
                                <button type="submit" class="btn-s btn-pri"><i class="bi bi-check-lg"></i> Saglabāt</button>
                            </div>
                        </div>
                    </form>

                    <div class="s-card">
                        <div class="s-card-title"><i class="bi bi-database"></i> Dati un privātums</div>
                        <div class="s-card-sub">GDPR tiesības un datu pārvaldība</div>
                        <form action="{{ route('account.data-export') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn-s btn-out" style="height:38px;font-size:.85rem;">
                                <i class="bi bi-download"></i> Lejupielādēt manus datus
                            </button>
                        </form>
                        <small style="font-size:.75rem;color:var(--t4);display:block;margin-top:.5rem;">
                            Saņem visus savus datus JSON formātā
                        </small>
                    </div>
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
    // Tabs
    function switchTab(idx, el) {
        document.querySelectorAll('.prof-tab').forEach(function(t){ t.classList.remove('active'); });
        document.querySelectorAll('.tab-panel').forEach(function(p){ p.classList.remove('active'); });
        el.classList.add('active');
        document.getElementById('tab' + idx).classList.add('active');
        window.location.hash = ['profile','security','privacy'][idx];
    }
    // Init from hash
    (function(){
        var map = {'#security':1,'#privacy':2};
        var h = window.location.hash;
        if (map[h] !== undefined) {
            var tabs = document.querySelectorAll('.prof-tab');
            switchTab(map[h], tabs[map[h]]);
        }
    })();

    // Password toggle
    function togglePw(id, btn) {
        var inp = document.getElementById(id);
        var isPass = inp.type === 'password';
        inp.type = isPass ? 'text' : 'password';
        btn.querySelector('i').className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
    }

    // Password strength
    function checkStrength(val) {
        var score = 0;
        var len = val.length >= 8; if (len) score++;
        var cas = /[a-z]/.test(val) && /[A-Z]/.test(val); if (cas) score++;
        var num = /\d/.test(val); if (num) score++;
        var sym = /[^a-zA-Z0-9]/.test(val); if (sym) score++;

        var colors = ['#EF4444','#F59E0B','#F59E0B','#10b981','#10b981'];
        var fill = document.getElementById('pwFill');
        fill.style.width = (score * 25) + '%';
        fill.style.background = colors[score] || '#e2e8f0';

        setRule('pwLen', len); setRule('pwCase', cas); setRule('pwNum', num); setRule('pwSym', sym);
    }
    function setRule(id, met) {
        var el = document.getElementById(id);
        el.classList.toggle('met', met);
        el.querySelector('i').className = met ? 'bi bi-check-circle-fill' : 'bi bi-circle';
    }

    // Password match
    function checkMatch() {
        var pw = document.getElementById('pwNew').value;
        var conf = document.getElementById('pwConfirm').value;
        var msg = document.getElementById('pwMatchMsg');
        if (!conf) { msg.textContent = ''; return; }
        if (pw === conf) { msg.innerHTML = '<span style="color:var(--suc);"><i class="bi bi-check-circle-fill"></i> Paroles sakrīt</span>'; }
        else { msg.innerHTML = '<span style="color:var(--dan);"><i class="bi bi-x-circle"></i> Paroles nesakrīt</span>'; }
    }

    // Toggle switch
    function toggleSwitch(el) {
        el.classList.toggle('on');
        el.setAttribute('aria-checked', el.classList.contains('on'));
    }

    // Avatar preview
    function previewAvatar(input) {
        if (!input.files[0]) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var prev = document.getElementById('avatarPreview');
            if (prev.tagName === 'IMG') { prev.src = e.target.result; }
            else { prev.outerHTML = '<img src="' + e.target.result + '" class="av-img" id="avatarPreview">'; }
        };
        reader.readAsDataURL(input.files[0]);
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
