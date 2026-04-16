<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ievietot sludinājumu — {{ config('app.name', 'SludinajumuPortals') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--warn:#F59E0B;--dan:#EF4444;
            --bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t2:#334155;--t3:#64748b;--t4:#94a3b8;--t5:#cbd5e1;
            --bdr:#e2e8f0;--r:16px;--r-sm:12px;--r-xs:8px;
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--t2);-webkit-font-smoothing:antialiased;}

        /* Nav */
        .sp-nav{background:var(--wh);border-bottom:1px solid var(--bdr);position:sticky;top:0;z-index:100;box-shadow:0 1px 2px rgba(0,0,0,.05);}
        .sp-nav .navbar-brand{font-weight:800;font-size:1.25rem;color:var(--dk);display:flex;align-items:center;gap:.5rem;padding:.75rem 0;}
        .sp-nav .brand-sq{width:32px;height:32px;background:var(--pri);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;}
        .sp-nav .nav-link{color:var(--t2);font-weight:500;font-size:.87rem;padding:.4rem .7rem;border-radius:var(--r-xs);transition:.15s;}
        .sp-nav .nav-link:hover{color:var(--pri);background:var(--pri-lt);}

        /* Progress */
        .wiz-progress{background:var(--wh);border-bottom:1px solid var(--bdr);padding:1rem 0;position:sticky;top:56px;z-index:90;}
        .wiz-steps{display:flex;justify-content:center;gap:0;max-width:600px;margin:0 auto;position:relative;}
        .wiz-step{flex:1;text-align:center;position:relative;cursor:pointer;}
        .wiz-step-circle{width:40px;height:40px;border-radius:50%;border:2px solid var(--bdr);background:var(--wh);display:flex;align-items:center;justify-content:center;margin:0 auto .4rem;font-weight:700;font-size:.85rem;color:var(--t4);transition:.3s;position:relative;z-index:2;}
        .wiz-step.active .wiz-step-circle{border-color:var(--pri);background:var(--pri);color:#fff;}
        .wiz-step.done .wiz-step-circle{border-color:var(--suc);background:var(--suc);color:#fff;}
        .wiz-step-label{font-size:.72rem;font-weight:600;color:var(--t4);transition:.2s;}
        .wiz-step.active .wiz-step-label{color:var(--pri);}
        .wiz-step.done .wiz-step-label{color:var(--suc);}
        .wiz-line{position:absolute;top:20px;left:50%;right:-50%;height:2px;background:var(--bdr);z-index:1;}
        .wiz-step:last-child .wiz-line{display:none;}
        .wiz-step.done .wiz-line{background:var(--suc);}
        .wiz-mob{display:none;text-align:center;font-size:.85rem;font-weight:600;color:var(--t2);}
        .wiz-mob-bar{height:4px;background:var(--bdr);border-radius:99px;margin-top:.5rem;overflow:hidden;}
        .wiz-mob-fill{height:100%;background:var(--pri);border-radius:99px;transition:width .4s;}

        /* Card */
        .wiz-card{background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r);padding:2rem 2.25rem;box-shadow:0 1px 3px rgba(0,0,0,.04);}
        .wiz-title{font-family:'Plus Jakarta Sans','Inter',sans-serif;font-size:1.4rem;font-weight:800;color:var(--dk);margin-bottom:.3rem;}
        .wiz-sub{font-size:.9rem;color:var(--t3);margin-bottom:1.75rem;}

        /* Sidebar */
        .wiz-sidebar{position:sticky;top:130px;}
        .wiz-user{display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;}
        .wiz-user-av{width:40px;height:40px;border-radius:50%;background:var(--pri-lt);display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:1.1rem;}
        .wiz-draft{display:flex;align-items:center;gap:.4rem;font-size:.78rem;color:var(--t4);padding:.5rem .75rem;background:var(--bg);border-radius:var(--r-xs);margin-bottom:1rem;}
        .wiz-tips{background:#fffbeb;border:1px solid #fde68a;border-radius:var(--r-sm);padding:1rem;}
        .wiz-tips h6{font-size:.82rem;font-weight:700;color:#92400e;margin-bottom:.4rem;}
        .wiz-tips p{font-size:.78rem;color:#a16207;margin:0;line-height:1.5;}

        /* Inputs */
        .f-label{font-size:.85rem;font-weight:600;color:var(--t1);margin-bottom:.4rem;display:block;}
        .f-label .req{color:var(--dan);margin-left:2px;}
        .f-input{width:100%;height:48px;padding:0 1rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-size:.9rem;color:var(--t1);transition:.2s;background:var(--wh);outline:none;}
        .f-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
        .f-input.is-err{border-color:var(--dan);}
        .f-input.is-ok{border-color:var(--suc);}
        .f-input-lg{height:56px;font-size:1.05rem;font-weight:600;}
        .f-textarea{min-height:140px;padding:.85rem 1rem;resize:vertical;height:auto;}
        .f-help{font-size:.75rem;color:var(--t4);margin-top:.35rem;}
        .f-err{font-size:.75rem;color:var(--dan);margin-top:.35rem;display:flex;align-items:center;gap:.3rem;}
        .f-counter{font-size:.72rem;color:var(--t4);text-align:right;margin-top:.2rem;}
        .f-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center;}
        .f-prefix{position:relative;}
        .f-prefix-text{position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:1rem;font-weight:700;color:var(--t4);pointer-events:none;}
        .f-prefix .f-input{padding-left:2.25rem;}

        /* Category tiles */
        .cat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;}
        .cat-tile{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.25rem .75rem;border:2px solid var(--bdr);border-radius:var(--r-sm);cursor:pointer;transition:.2s;text-align:center;background:var(--wh);}
        .cat-tile:hover{border-color:var(--pri);background:var(--pri-lt);transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.06);}
        .cat-tile.sel{border-color:var(--pri);background:var(--pri-lt);box-shadow:0 0 0 3px rgba(37,99,235,.15);}
        .cat-tile-icon{width:48px;height:48px;border-radius:14px;background:var(--pri-lt);display:flex;align-items:center;justify-content:center;margin-bottom:.5rem;font-size:1.3rem;color:var(--pri);transition:.2s;}
        .cat-tile.sel .cat-tile-icon{background:var(--pri);color:#fff;}
        .cat-tile-name{font-size:.82rem;font-weight:600;color:var(--t1);}

        /* Sub/child chips */
        .chip-row{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:1rem;}
        .chip{padding:.45rem 1rem;border:1.5px solid var(--bdr);border-radius:99px;font-size:.82rem;font-weight:500;color:var(--t2);cursor:pointer;transition:.15s;background:var(--wh);}
        .chip:hover{border-color:var(--pri);color:var(--pri);}
        .chip.sel{background:var(--pri);border-color:var(--pri);color:#fff;}

        /* Condition cards */
        .cond-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:.5rem;}
        .cond-card{padding:.85rem;border:2px solid var(--bdr);border-radius:var(--r-sm);cursor:pointer;transition:.2s;text-align:center;}
        .cond-card:hover{border-color:var(--pri);}
        .cond-card.sel{border-color:var(--pri);background:var(--pri-lt);}
        .cond-card i{font-size:1.3rem;color:var(--pri);margin-bottom:.3rem;display:block;}
        .cond-card .cond-name{font-size:.85rem;font-weight:600;color:var(--t1);}
        .cond-card .cond-desc{font-size:.7rem;color:var(--t4);}

        /* Price toggle */
        .toggle-group{display:flex;gap:0;border:1.5px solid var(--bdr);border-radius:var(--r-xs);overflow:hidden;margin-top:.5rem;}
        .toggle-opt{flex:1;padding:.5rem;text-align:center;font-size:.8rem;font-weight:600;color:var(--t3);cursor:pointer;transition:.15s;border-right:1px solid var(--bdr);}
        .toggle-opt:last-child{border-right:none;}
        .toggle-opt:hover{background:var(--pri-lt);}
        .toggle-opt.sel{background:var(--pri);color:#fff;}

        /* Photo upload */
        .photo-zone{border:2px dashed var(--bdr);border-radius:var(--r);padding:2.5rem;text-align:center;cursor:pointer;transition:.2s;min-height:200px;display:flex;flex-direction:column;align-items:center;justify-content:center;}
        .photo-zone:hover,.photo-zone.drag{border-color:var(--pri);background:var(--pri-lt);}
        .photo-zone i{font-size:2.5rem;color:var(--t5);margin-bottom:.75rem;}
        .photo-zone.drag i{color:var(--pri);}
        .photo-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-top:1rem;}
        .photo-thumb{position:relative;aspect-ratio:1;border-radius:var(--r-sm);overflow:hidden;background:#f1f5f9;}
        .photo-thumb img{width:100%;height:100%;object-fit:cover;}
        .photo-thumb .photo-badge{position:absolute;top:6px;left:6px;background:var(--pri);color:#fff;font-size:.65rem;font-weight:700;padding:.15rem .45rem;border-radius:99px;}
        .photo-thumb .photo-del{position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,.6);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.7rem;opacity:0;transition:.2s;}
        .photo-thumb:hover .photo-del{opacity:1;}

        /* Breadcrumb inside step */
        .cat-bc{display:flex;align-items:center;gap:.35rem;margin-bottom:1rem;flex-wrap:wrap;}
        .cat-bc span{font-size:.82rem;font-weight:500;color:var(--t4);cursor:pointer;transition:.15s;}
        .cat-bc span:hover{color:var(--pri);}
        .cat-bc .cat-bc-sep{color:var(--t5);cursor:default;}
        .cat-bc .cat-bc-cur{color:var(--t1);font-weight:600;cursor:default;}

        /* Preview */
        .preview-img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:var(--r-sm);}
        .check-list{list-style:none;padding:0;margin:0;}
        .check-list li{display:flex;align-items:center;gap:.5rem;padding:.5rem 0;font-size:.88rem;border-bottom:1px solid var(--bdr);}
        .check-list li:last-child{border:none;}
        .check-ok{color:var(--suc);font-size:1.1rem;}
        .check-no{color:var(--t5);font-size:1.1rem;}

        /* Buttons */
        .btn-wiz{height:48px;border-radius:var(--r-xs);font-weight:600;font-size:.92rem;padding:0 2rem;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;border:none;cursor:pointer;transition:.15s;}
        .btn-wiz:active{transform:scale(.98);}
        .btn-pri{background:var(--pri);color:#fff;}
        .btn-pri:hover{background:var(--pri-dk);color:#fff;}
        .btn-pri:disabled{opacity:.5;cursor:not-allowed;transform:none;}
        .btn-out{background:none;border:1.5px solid var(--bdr);color:var(--t2);}
        .btn-out:hover{border-color:var(--pri);color:var(--pri);}
        .btn-suc{background:var(--suc);color:#fff;}
        .btn-suc:hover{background:#059669;color:#fff;}
        .wiz-footer{display:flex;justify-content:space-between;align-items:center;margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--bdr);}

        /* Step transitions */
        .step-panel{display:none;animation:stepIn .3s ease;}
        .step-panel.active{display:block;}
        @keyframes stepIn{from{opacity:0;transform:translateX(20px);}to{opacity:1;transform:translateX(0);}}

        /* Toast */
        .toast-wrap{position:fixed;top:20px;right:20px;z-index:3000;}
        .toast-msg{background:var(--t1);color:#fff;padding:.65rem 1.1rem;border-radius:var(--r-xs);font-size:.85rem;font-weight:500;box-shadow:0 12px 32px rgba(0,0,0,.1);animation:toastIn .3s ease,toastOut .3s ease 2.7s forwards;}
        @keyframes toastIn{from{opacity:0;transform:translateX(30px);}to{opacity:1;transform:translateX(0);}}
        @keyframes toastOut{from{opacity:1;}to{opacity:0;}}

        /* Footer */
        .sp-foot{background:var(--dk);color:rgba(255,255,255,.6);padding:2rem 0 1rem;margin-top:3rem;}
        .sp-foot a{color:rgba(255,255,255,.5);text-decoration:none;font-size:.83rem;}

        @media(max-width:991.98px){
            .wiz-steps{display:none;}
            .wiz-mob{display:block;}
            .wiz-sidebar-col{display:none;}
        }
        @media(max-width:767.98px){
            .cat-grid{grid-template-columns:repeat(2,1fr);}
            .cond-grid{grid-template-columns:1fr 1fr;}
            .photo-grid{grid-template-columns:repeat(3,1fr);}
            .wiz-card{padding:1.5rem 1.25rem;}
            .wiz-footer{flex-direction:column-reverse;gap:.75rem;}
            .wiz-footer .btn-wiz{width:100%;}
        }
    </style>
</head>
<body>
    <div class="toast-wrap" id="toastWrap"></div>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg sp-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/home') }}">
                <span class="brand-sq"><i class="bi bi-megaphone-fill"></i></span>
                {{ config('app.name', 'SludinajumuPortals') }}
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ url('/home') }}"><i class="bi bi-house-door me-1"></i> Sākums</a>
            </div>
        </div>
    </nav>

    {{-- Progress Bar --}}
    <div class="wiz-progress">
        <div class="container">
            <div class="wiz-steps" id="wizSteps">
                <div class="wiz-step active" onclick="goToStep(0)">
                    <div class="wiz-line"></div>
                    <div class="wiz-step-circle">1</div>
                    <div class="wiz-step-label">Kategorija</div>
                </div>
                <div class="wiz-step" onclick="goToStep(1)">
                    <div class="wiz-line"></div>
                    <div class="wiz-step-circle">2</div>
                    <div class="wiz-step-label">Detaļas</div>
                </div>
                <div class="wiz-step" onclick="goToStep(2)">
                    <div class="wiz-line"></div>
                    <div class="wiz-step-circle">3</div>
                    <div class="wiz-step-label">Foto</div>
                </div>
                <div class="wiz-step" onclick="goToStep(3)">
                    <div class="wiz-line"></div>
                    <div class="wiz-step-circle">4</div>
                    <div class="wiz-step-label">Kontakti</div>
                </div>
            </div>
            <div class="wiz-mob" id="wizMob">
                <span>Solis <span id="mobStep">1</span> no 4</span>
                <div class="wiz-mob-bar"><div class="wiz-mob-fill" id="mobFill" style="width:25%"></div></div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top:1.5rem;margin-bottom:2rem;">
        <div class="row g-4">
            {{-- Sidebar --}}
            <div class="col-lg-3 wiz-sidebar-col">
                <div class="wiz-sidebar">
                    <div class="wiz-user">
                        <div class="wiz-user-av"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div style="font-size:.9rem;font-weight:700;color:var(--t1);">{{ auth()->user()->name }}</div>
                            <div style="font-size:.75rem;color:var(--t4);">{{ \App\Models\Advertisement::where('user_id', auth()->id())->count() }} sludinājumi</div>
                        </div>
                    </div>
                    <div class="wiz-draft" id="draftStatus">
                        <i class="bi bi-clock"></i> <span>Melnraksts saglabāts</span>
                    </div>
                    <div class="wiz-tips" id="wizTips">
                        <h6><i class="bi bi-lightbulb me-1"></i> Padoms</h6>
                        <p>Izvēlies precīzāko kategoriju — tā tavs sludinājums sasniegs pareizo auditoriju.</p>
                    </div>
                </div>
            </div>

            {{-- Main --}}
            <div class="col-lg-9">
                @if($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-sm);padding:1rem 1.25rem;margin-bottom:1rem;">
                        <div style="font-size:.88rem;font-weight:600;color:#dc2626;margin-bottom:.3rem;"><i class="bi bi-exclamation-triangle me-1"></i> Lūdzu izlabojiet kļūdas:</div>
                        @foreach($errors->all() as $err)
                            <div style="font-size:.82rem;color:#b91c1c;">• {{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data" id="adForm">
                    @csrf

                    {{-- ===== STEP 1: Category ===== --}}
                    <div class="step-panel active" id="step0">
                        <div class="wiz-card">
                            <div class="wiz-title">Ko tu pārdod?</div>
                            <div class="wiz-sub">Izvēlies kategoriju, lai pircēji varētu atrast tavu sludinājumu</div>

                            <div id="catBreadcrumb" class="cat-bc" style="display:none;"></div>

                            @php
                                $catIcons = [
                                    'Transports'=>'bi-truck-front-fill','Nekustamais ipasums'=>'bi-buildings-fill',
                                    'Elektronikas'=>'bi-cpu-fill','Majai un darzam'=>'bi-tree-fill',
                                    'Apgerbs'=>'bi-bag-fill','Sports un hobiji'=>'bi-dribbble',
                                    'Dzivnieki'=>'bi-bug-fill','Darbs'=>'bi-briefcase-fill',
                                ];
                            @endphp

                            {{-- Categories --}}
                            <div id="catGrid" class="cat-grid">
                                @foreach($categories as $cat)
                                    <div class="cat-tile" data-id="{{ $cat->id }}" data-name="{{ $cat->name }}" onclick="selectCategory(this)">
                                        <div class="cat-tile-icon"><i class="bi {{ $catIcons[$cat->name] ?? 'bi-tag-fill' }}"></i></div>
                                        <div class="cat-tile-name">{{ $cat->name }}</div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Subcategories --}}
                            <div id="subChips" style="display:none;">
                                <label class="f-label" style="margin-bottom:.5rem;">Apakškategorija <span class="req">*</span></label>
                                <div class="chip-row" id="subChipRow"></div>
                            </div>

                            {{-- Child categories --}}
                            <div id="childChips" style="display:none;">
                                <label class="f-label" style="margin-top:1rem;margin-bottom:.5rem;">Precizēt</label>
                                <div class="chip-row" id="childChipRow"></div>
                            </div>

                            <input type="hidden" name="category_id" id="category_id">
                            <input type="hidden" name="subcategory_id" id="subcategory_id">
                            <input type="hidden" name="childcategory_id" id="childcategory_id">

                            <div class="wiz-footer">
                                <div></div>
                                <button type="button" class="btn-wiz btn-pri" onclick="nextStep()" id="btnNext0" disabled>
                                    Turpināt <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ===== STEP 2: Details ===== --}}
                    <div class="step-panel" id="step1">
                        <div class="wiz-card">
                            <div class="wiz-title">Apraksti savu preci</div>
                            <div class="wiz-sub">Detalizēts apraksts palīdz pārdot ātrāk</div>

                            <div style="margin-bottom:1.25rem;">
                                <label class="f-label">Nosaukums <span class="req">*</span></label>
                                <input type="text" name="name" id="adName" class="f-input f-input-lg" placeholder="piem. BMW 320d 2018 Navi ādas salons" maxlength="80" oninput="countChars(this,'nameCount',80)">
                                <div class="f-counter"><span id="nameCount">0</span>/80</div>
                            </div>

                            <div style="margin-bottom:1.25rem;">
                                <label class="f-label">Apraksts <span class="req">*</span></label>
                                <textarea name="description" id="adDesc" class="f-input f-textarea" placeholder="Apraksti preci detalizēti — stāvokli, īpašības, iemeslu pārdošanai..." maxlength="2000" oninput="countChars(this,'descCount',2000);autoGrow(this)"></textarea>
                                <div class="f-counter"><span id="descCount">0</span>/2000</div>
                            </div>

                            <div class="row g-3" style="margin-bottom:1.25rem;">
                                <div class="col-md-6">
                                    <label class="f-label">Cena <span class="req">*</span></label>
                                    <div class="f-prefix">
                                        <span class="f-prefix-text">&euro;</span>
                                        <input type="number" name="price" id="adPrice" class="f-input" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                    <div class="toggle-group" style="margin-top:.5rem;">
                                        <div class="toggle-opt sel" onclick="selectToggle(this,'price_status','Sarunājama')">Sarunājama</div>
                                        <div class="toggle-opt" onclick="selectToggle(this,'price_status','Fiksēta')">Fiksēta</div>
                                        <div class="toggle-opt" onclick="selectToggle(this,'price_status','Bezmaksas')">Bezmaksas</div>
                                    </div>
                                    <input type="hidden" name="price_status" id="price_status" value="Sarunājama">
                                </div>
                                <div class="col-md-6">
                                    <label class="f-label">Stāvoklis <span class="req">*</span></label>
                                    <div class="cond-grid">
                                        <div class="cond-card" onclick="selectCond(this,'new')">
                                            <i class="bi bi-stars"></i>
                                            <div class="cond-name">Jauns</div>
                                            <div class="cond-desc">Nelietots, oriģinālajā iepakojumā</div>
                                        </div>
                                        <div class="cond-card" onclick="selectCond(this,'likenew')">
                                            <i class="bi bi-check-circle"></i>
                                            <div class="cond-name">Kā jauns</div>
                                            <div class="cond-desc">Minimālas lietošanas pazīmes</div>
                                        </div>
                                        <div class="cond-card" onclick="selectCond(this,'used')">
                                            <i class="bi bi-arrow-repeat"></i>
                                            <div class="cond-name">Lietots</div>
                                            <div class="cond-desc">Normālas lietošanas pazīmes</div>
                                        </div>
                                        <div class="cond-card" onclick="selectCond(this,'parts')">
                                            <i class="bi bi-tools"></i>
                                            <div class="cond-name">Rezerves daļām</div>
                                            <div class="cond-desc">Prece ar defektiem</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_condition" id="product_condition">
                                </div>
                            </div>

                            <div class="wiz-footer">
                                <button type="button" class="btn-wiz btn-out" onclick="prevStep()">
                                    <i class="bi bi-arrow-left"></i> Atpakaļ
                                </button>
                                <button type="button" class="btn-wiz btn-pri" onclick="nextStep()">
                                    Turpināt <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ===== STEP 3: Photos ===== --}}
                    <div class="step-panel" id="step2">
                        <div class="wiz-card">
                            <div class="wiz-title">Pievieno fotogrāfijas</div>
                            <div class="wiz-sub">Sludinājumi ar vairāk foto pārdodas 2x ātrāk</div>

                            <div class="photo-zone" id="photoZone" onclick="document.getElementById('photoInput').click()" ondragover="event.preventDefault();this.classList.add('drag')" ondragleave="this.classList.remove('drag')" ondrop="handleDrop(event)">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <div style="font-size:.95rem;font-weight:600;color:var(--t2);">Velc bildes šeit vai noklikšķini</div>
                                <div style="font-size:.78rem;color:var(--t4);margin-top:.3rem;">Līdz 12 fotogrāfijām, max 5MB katra (JPG, PNG, WebP)</div>
                                <div id="photoCountLabel" style="font-size:.82rem;font-weight:600;color:var(--pri);margin-top:.5rem;display:none;">0 / 12</div>
                            </div>
                            <input type="file" id="photoInput" accept="image/jpeg,image/png,image/webp" multiple style="display:none;" onchange="handlePhotos(this.files)">

                            <div class="photo-grid" id="photoGrid"></div>

                            {{-- Hidden container for actual file inputs sent to server --}}
                            <div id="hiddenFileInputs"></div>

                            <div class="wiz-footer">
                                <button type="button" class="btn-wiz btn-out" onclick="prevStep()">
                                    <i class="bi bi-arrow-left"></i> Atpakaļ
                                </button>
                                <button type="button" class="btn-wiz btn-pri" onclick="nextStep()">
                                    Turpināt <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ===== STEP 4: Location & Contact ===== --}}
                    <div class="step-panel" id="step3">
                        <div class="wiz-card">
                            <div class="wiz-title">Atrašanās vieta un kontakti</div>
                            <div class="wiz-sub">Kur atrodas prece un kā ar tevi sazināties</div>

                            <div style="margin-bottom:1.25rem;">
                                <label class="f-label">Atrašanās vieta <span class="req">*</span></label>
                                <input type="text" name="listing_location" id="adLocation" class="f-input" placeholder="piem. Rīga, Tukums, Liepāja...">
                                <div class="f-help">Ieraksti pilsētu vai novadu, kur prece ir pieejama</div>
                            </div>

                            <div class="row g-3" style="margin-bottom:1.25rem;">
                                <div class="col-md-4">
                                    <label class="f-label">Valsts <span class="req">*</span></label>
                                    <select name="country_id" id="country_id" class="f-input f-select" onchange="loadStates(this.value)">
                                        <option value="">Izvēlies valsti</option>
                                        @foreach(App\Models\Country::all() as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="f-label">Reģions</label>
                                    <select name="state_id" id="state_id" class="f-input f-select" onchange="loadCities(this.value)">
                                        <option value="">Izvēlies reģionu</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="f-label">Pilsēta</label>
                                    <select name="city_id" id="city_id" class="f-input f-select">
                                        <option value="">Izvēlies pilsētu</option>
                                    </select>
                                </div>
                            </div>

                            <div style="margin-bottom:1.25rem;">
                                <label class="f-label">Tālrunis</label>
                                <input type="tel" name="phone_number" id="adPhone" class="f-input" placeholder="+371 2X XXX XXX">
                                <div class="f-help">Tiks rādīts sludinājumā. Atstāj tukšu, ja nevēlies dalīties.</div>
                            </div>

                            <div style="margin-bottom:1.25rem;">
                                <label class="f-label">Video saite <span style="font-weight:400;color:var(--t4);">(neobligāts)</span></label>
                                <input type="url" name="link" id="adLink" class="f-input" placeholder="https://youtube.com/...">
                            </div>

                            <div class="wiz-footer">
                                <button type="button" class="btn-wiz btn-out" onclick="prevStep()">
                                    <i class="bi bi-arrow-left"></i> Atpakaļ
                                </button>
                                <button type="button" class="btn-wiz btn-pri" onclick="nextStep()">
                                    Pārskats <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ===== STEP 5: Preview ===== --}}
                    <div class="step-panel" id="step4">
                        <div class="wiz-card">
                            <div class="wiz-title">Pārskats pirms publicēšanas</div>
                            <div class="wiz-sub">Pārliecinies, ka viss ir pareizi</div>

                            <div class="row g-4">
                                <div class="col-md-5">
                                    <div id="prevImg" style="aspect-ratio:4/3;background:#f1f5f9;border-radius:var(--r-sm);overflow:hidden;display:flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-image" style="font-size:2.5rem;color:var(--t5);"></i>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div id="prevTitle" style="font-size:1.15rem;font-weight:700;color:var(--dk);margin-bottom:.5rem;"></div>
                                    <div id="prevPrice" style="font-size:1.5rem;font-weight:800;color:var(--pri);margin-bottom:.75rem;"></div>
                                    <div id="prevMeta" style="font-size:.82rem;color:var(--t3);margin-bottom:1rem;"></div>
                                </div>
                            </div>

                            <div style="margin-top:1.5rem;">
                                <div class="sec-t" style="font-size:.95rem;font-weight:700;color:var(--t1);margin-bottom:.75rem;"><i class="bi bi-check2-all" style="color:var(--suc);"></i> Pārbaudes saraksts</div>
                                <ul class="check-list" id="checkList"></ul>
                            </div>

                            <div class="wiz-footer">
                                <button type="button" class="btn-wiz btn-out" onclick="prevStep()">
                                    <i class="bi bi-arrow-left"></i> Labot
                                </button>
                                <button type="submit" class="btn-wiz btn-suc" id="publishBtn" style="height:52px;padding:0 2.5rem;font-size:1rem;">
                                    <i class="bi bi-rocket-takeoff"></i> Publicēt sludinājumu
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="sp-foot">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span style="font-size:.8rem;">&copy; {{ date('Y') }} {{ config('app.name') }}. Visas tiesības aizsargātas.</span>
                <a href="{{ url('/home') }}">Sākums</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    var step = 0, totalSteps = 5;
    var photos = []; // {file, url}
    var tips = [
        'Izvēlies precīzāko kategoriju — tā tavs sludinājums sasniegs pareizo auditoriju.',
        'Skaidrs nosaukums saņem 3x vairāk skatījumu. Norādi marku, modeli, izmēru.',
        'Sludinājumi ar 3 foto pārdodas 2x ātrāk. Izmanto dabīgu gaismu.',
        'Norādi precīzu atrašanās vietu — pircēji meklē savā tuvumā.',
        'Pārliecinies, ka visa informācija ir pareiza pirms publicēšanas.'
    ];

    // === Step Navigation ===
    function goToStep(n) {
        if (n > step) return; // can only go back
        step = n;
        renderStep();
    }
    function nextStep() {
        if (!validateStep(step)) return;
        if (step < totalSteps - 1) { step++; renderStep(); }
    }
    function prevStep() {
        if (step > 0) { step--; renderStep(); }
    }
    function renderStep() {
        document.querySelectorAll('.step-panel').forEach(function(p, i) {
            p.classList.toggle('active', i === step);
        });
        // Progress
        document.querySelectorAll('.wiz-step').forEach(function(s, i) {
            s.classList.remove('active', 'done');
            if (i < step) s.classList.add('done');
            if (i === step) s.classList.add('active');
            var circle = s.querySelector('.wiz-step-circle');
            circle.innerHTML = i < step ? '<i class="bi bi-check-lg"></i>' : (i + 1);
        });
        // Mobile
        document.getElementById('mobStep').textContent = step + 1;
        document.getElementById('mobFill').style.width = ((step + 1) / totalSteps * 100) + '%';
        // Tips
        document.querySelector('.wiz-tips p').textContent = tips[step];
        // Preview
        if (step === 4) buildPreview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        saveDraft();
    }

    // === Validation ===
    function validateStep(n) {
        if (n === 0) {
            if (!document.getElementById('category_id').value) { showToast('Izvēlies kategoriju!'); return false; }
            if (!document.getElementById('subcategory_id').value) { showToast('Izvēlies apakškategoriju!'); return false; }
        }
        if (n === 1) {
            if (!document.getElementById('adName').value.trim()) { shakeField('adName'); return false; }
            if (!document.getElementById('adDesc').value.trim()) { shakeField('adDesc'); return false; }
            if (!document.getElementById('adPrice').value) { shakeField('adPrice'); return false; }
            if (!document.getElementById('product_condition').value) { showToast('Izvēlies stāvokli!'); return false; }
        }
        if (n === 2) {
            if (photos.length === 0) { showToast('Pievieno vismaz 1 fotogrāfiju!'); return false; }
        }
        if (n === 3) {
            if (!document.getElementById('adLocation').value.trim()) { shakeField('adLocation'); return false; }
            if (!document.getElementById('country_id').value) { shakeField('country_id'); return false; }
        }
        return true;
    }
    function shakeField(id) {
        var el = document.getElementById(id);
        el.classList.add('is-err');
        el.style.animation = 'shake .4s';
        el.focus();
        setTimeout(function(){ el.style.animation = ''; }, 400);
        showToast('Aizpildi obligāto lauku!');
    }

    // === Category Selection ===
    function selectCategory(el) {
        document.querySelectorAll('.cat-tile').forEach(function(t){ t.classList.remove('sel'); });
        el.classList.add('sel');
        var id = el.dataset.id;
        var name = el.dataset.name;
        document.getElementById('category_id').value = id;
        document.getElementById('subcategory_id').value = '';
        document.getElementById('childcategory_id').value = '';
        document.getElementById('btnNext0').disabled = true;
        // Load subcategories
        $.get('/get-subcategories/' + id, function(data) {
            var row = document.getElementById('subChipRow');
            row.innerHTML = '';
            data.forEach(function(sub) {
                var chip = document.createElement('div');
                chip.className = 'chip';
                chip.textContent = sub.name;
                chip.dataset.id = sub.id;
                chip.onclick = function(){ selectSub(this, id, name); };
                row.appendChild(chip);
            });
            document.getElementById('subChips').style.display = data.length ? 'block' : 'none';
            document.getElementById('childChips').style.display = 'none';
            updateCatBreadcrumb([name]);
        });
    }
    function selectSub(el, catId, catName) {
        document.querySelectorAll('#subChipRow .chip').forEach(function(c){ c.classList.remove('sel'); });
        el.classList.add('sel');
        document.getElementById('subcategory_id').value = el.dataset.id;
        document.getElementById('childcategory_id').value = '';
        document.getElementById('btnNext0').disabled = false;
        // Load child categories
        $.get('/get-childcategories/' + el.dataset.id, function(data) {
            var row = document.getElementById('childChipRow');
            row.innerHTML = '';
            data.forEach(function(ch) {
                var chip = document.createElement('div');
                chip.className = 'chip';
                chip.textContent = ch.name;
                chip.dataset.id = ch.id;
                chip.onclick = function(){ selectChild(this); };
                row.appendChild(chip);
            });
            document.getElementById('childChips').style.display = data.length ? 'block' : 'none';
            updateCatBreadcrumb([catName, el.textContent]);
        });
    }
    function selectChild(el) {
        document.querySelectorAll('#childChipRow .chip').forEach(function(c){ c.classList.remove('sel'); });
        el.classList.add('sel');
        document.getElementById('childcategory_id').value = el.dataset.id;
    }
    function updateCatBreadcrumb(parts) {
        var bc = document.getElementById('catBreadcrumb');
        bc.style.display = 'flex';
        bc.innerHTML = parts.map(function(p, i) {
            if (i < parts.length - 1) return '<span>' + p + '</span><span class="cat-bc-sep"><i class="bi bi-chevron-right"></i></span>';
            return '<span class="cat-bc-cur">' + p + '</span>';
        }).join('');
    }

    // === Condition ===
    function selectCond(el, val) {
        document.querySelectorAll('.cond-card').forEach(function(c){ c.classList.remove('sel'); });
        el.classList.add('sel');
        document.getElementById('product_condition').value = val;
    }

    // === Toggle Group ===
    function selectToggle(el, field, val) {
        el.parentElement.querySelectorAll('.toggle-opt').forEach(function(o){ o.classList.remove('sel'); });
        el.classList.add('sel');
        document.getElementById(field).value = val;
    }

    // === Photos (up to 12) ===
    var MAX_PHOTOS = 12;

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('photoZone').classList.remove('drag');
        handlePhotos(e.dataTransfer.files);
    }
    function handlePhotos(files) {
        for (var i = 0; i < files.length && photos.length < MAX_PHOTOS; i++) {
            var f = files[i];
            if (f.size > 5 * 1024 * 1024) { showToast('Fails pārāk liels (max 5MB)!'); continue; }
            if (!f.type.match(/^image\/(jpeg|png|webp)$/)) { showToast('Atbalstīti formāti: JPG, PNG, WebP'); continue; }
            photos.push({ file: f, url: URL.createObjectURL(f) });
        }
        if (photos.length >= MAX_PHOTOS) showToast('Sasniegts maksimums — 12 fotogrāfijas');
        renderPhotos();
        syncPhotoInputs();
    }
    function renderPhotos() {
        var grid = document.getElementById('photoGrid');
        grid.innerHTML = '';
        photos.forEach(function(p, i) {
            var div = document.createElement('div');
            div.className = 'photo-thumb';
            div.innerHTML = '<img src="' + p.url + '">' +
                (i === 0 ? '<span class="photo-badge">Galvenā</span>' : '<span class="photo-badge" style="background:var(--t2);">' + (i+1) + '</span>') +
                '<button type="button" class="photo-del" onclick="removePhoto(' + i + ')"><i class="bi bi-x"></i></button>';
            grid.appendChild(div);
        });
        var zone = document.getElementById('photoZone');
        zone.style.display = photos.length >= MAX_PHOTOS ? 'none' : 'flex';
        var label = document.getElementById('photoCountLabel');
        if (photos.length > 0) {
            label.style.display = 'block';
            label.textContent = photos.length + ' / ' + MAX_PHOTOS;
        } else {
            label.style.display = 'none';
        }
    }
    function removePhoto(i) {
        URL.revokeObjectURL(photos[i].url);
        photos.splice(i, 1);
        renderPhotos();
        syncPhotoInputs();
    }
    function syncPhotoInputs() {
        var container = document.getElementById('hiddenFileInputs');
        container.innerHTML = '';
        var dt = new DataTransfer();
        photos.forEach(function(p) { dt.items.add(p.file); });
        var input = document.createElement('input');
        input.type = 'file';
        input.name = 'ad_images[]';
        input.multiple = true;
        input.style.display = 'none';
        input.files = dt.files;
        container.appendChild(input);
    }

    // === Location Dropdowns ===
    function loadStates(countryId) {
        if (!countryId) return;
        $.get('/get-states/' + countryId, function(data) {
            var sel = document.getElementById('state_id');
            sel.innerHTML = '<option value="">Izvēlies reģionu</option>';
            data.forEach(function(s) { sel.innerHTML += '<option value="' + s.id + '">' + s.name + '</option>'; });
        });
    }
    function loadCities(stateId) {
        if (!stateId) return;
        $.get('/get-cities/' + stateId, function(data) {
            var sel = document.getElementById('city_id');
            sel.innerHTML = '<option value="">Izvēlies pilsētu</option>';
            data.forEach(function(c) { sel.innerHTML += '<option value="' + c.id + '">' + c.name + '</option>'; });
        });
    }

    // === Preview ===
    function buildPreview() {
        document.getElementById('prevTitle').textContent = document.getElementById('adName').value || '(Nav nosaukuma)';
        var price = document.getElementById('adPrice').value;
        document.getElementById('prevPrice').textContent = price ? '€' + parseFloat(price).toFixed(2) : '—';
        var loc = document.getElementById('adLocation').value;
        document.getElementById('prevMeta').innerHTML = (loc ? '<i class="bi bi-geo-alt-fill me-1"></i>' + loc + ' · ' : '') + '<i class="bi bi-clock me-1"></i>Tikko publicēts';
        // Image
        var imgDiv = document.getElementById('prevImg');
        if (photos.length) {
            imgDiv.innerHTML = '<img src="' + photos[0].url + '" style="width:100%;height:100%;object-fit:cover;">';
        }
        // Checklist
        var checks = [
            { ok: !!document.getElementById('category_id').value, text: 'Kategorija izvēlēta' },
            { ok: (document.getElementById('adName').value || '').length >= 5, text: 'Nosaukums (min 5 rakstzīmes)' },
            { ok: (document.getElementById('adDesc').value || '').length >= 10, text: 'Apraksts (min 10 rakstzīmes)' },
            { ok: photos.length >= 1, text: 'Vismaz 1 fotogrāfija' },
            { ok: !!document.getElementById('adLocation').value, text: 'Atrašanās vieta norādīta' },
            { ok: !!document.getElementById('country_id').value, text: 'Valsts izvēlēta' },
        ];
        var list = document.getElementById('checkList');
        list.innerHTML = '';
        checks.forEach(function(c) {
            list.innerHTML += '<li><i class="bi ' + (c.ok ? 'bi-check-circle-fill check-ok' : 'bi-circle check-no') + '"></i> ' + c.text + '</li>';
        });
    }

    // === Helpers ===
    function countChars(el, countId, max) {
        document.getElementById(countId).textContent = el.value.length;
        el.classList.toggle('is-err', el.value.length > max);
    }
    function autoGrow(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 384) + 'px';
    }
    function showToast(msg) {
        var el = document.createElement('div');
        el.className = 'toast-msg';
        el.textContent = msg;
        document.getElementById('toastWrap').appendChild(el);
        setTimeout(function(){ el.remove(); }, 3200);
    }

    // === Draft Auto-save ===
    var draftTimer;
    function saveDraft() {
        clearTimeout(draftTimer);
        draftTimer = setTimeout(function() {
            var data = {
                step: step,
                category_id: document.getElementById('category_id').value,
                subcategory_id: document.getElementById('subcategory_id').value,
                name: document.getElementById('adName').value,
                description: document.getElementById('adDesc').value,
                price: document.getElementById('adPrice').value,
                price_status: document.getElementById('price_status').value,
                product_condition: document.getElementById('product_condition').value,
                listing_location: document.getElementById('adLocation').value,
                phone_number: document.getElementById('adPhone').value,
                link: document.getElementById('adLink').value,
                saved_at: Date.now()
            };
            localStorage.setItem('ad_draft_v1', JSON.stringify(data));
            var ds = document.getElementById('draftStatus');
            ds.innerHTML = '<i class="bi bi-check-circle" style="color:var(--suc);"></i> <span>Melnraksts saglabāts</span>';
        }, 500);
    }
    // Auto-save on input
    document.addEventListener('input', saveDraft);

    // Restore draft
    (function() {
        var raw = localStorage.getItem('ad_draft_v1');
        if (!raw) return;
        try {
            var d = JSON.parse(raw);
            if (Date.now() - d.saved_at > 7 * 24 * 3600 * 1000) { localStorage.removeItem('ad_draft_v1'); return; }
            if (d.name) document.getElementById('adName').value = d.name;
            if (d.description) document.getElementById('adDesc').value = d.description;
            if (d.price) document.getElementById('adPrice').value = d.price;
            if (d.listing_location) document.getElementById('adLocation').value = d.listing_location;
            if (d.phone_number) document.getElementById('adPhone').value = d.phone_number;
            if (d.link) document.getElementById('adLink').value = d.link;
            if (d.product_condition) document.getElementById('product_condition').value = d.product_condition;
            if (d.price_status) document.getElementById('price_status').value = d.price_status;
        } catch(e) {}
    })();

    // Shake animation
    var shakeStyle = document.createElement('style');
    shakeStyle.textContent = '@keyframes shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-6px)}40%,80%{transform:translateX(6px)}}';
    document.head.appendChild(shakeStyle);

    // Submit loading
    document.getElementById('adForm').addEventListener('submit', function() {
        var btn = document.getElementById('publishBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Publicē...';
        localStorage.removeItem('ad_draft_v1');
    });
    </script>
</body>
</html>
