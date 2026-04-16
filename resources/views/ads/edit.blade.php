@php use Illuminate\Support\Str; @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rediģēt — {{ Str::limit($ad->name, 30) }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{--pri:#2563EB;--pri-dk:#1E40AF;--pri-lt:#eff6ff;--suc:#10b981;--suc-lt:#dcfce7;--warn:#F59E0B;--warn-lt:#fef3c7;--dan:#EF4444;--bg:#F8FAFC;--wh:#fff;--dk:#0f172a;--t1:#1e293b;--t2:#334155;--t3:#64748b;--t4:#94a3b8;--t5:#cbd5e1;--bdr:#e2e8f0;--r:16px;--r-sm:12px;--r-xs:8px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--t2);-webkit-font-smoothing:antialiased;}

        .bc{display:flex;align-items:center;gap:.35rem;padding:1rem 0 .5rem;flex-wrap:wrap;}
        .bc a{color:var(--t4);text-decoration:none;font-size:.8rem;}.bc a:hover{color:var(--pri);}
        .bc .sep{color:var(--t5);font-size:.7rem;}.bc .cur{color:var(--t1);font-weight:500;font-size:.8rem;}

        .s-card{background:var(--wh);border:1px solid var(--bdr);border-radius:var(--r);padding:1.5rem;margin-bottom:1rem;}
        .s-title{font-size:1.05rem;font-weight:700;color:var(--dk);display:flex;align-items:center;gap:.4rem;margin-bottom:.2rem;}
        .s-title i{color:var(--pri);font-size:1.1rem;}
        .s-sub{font-size:.82rem;color:var(--t4);margin-bottom:1.25rem;}

        .f-label{font-size:.85rem;font-weight:600;color:var(--t1);margin-bottom:.35rem;display:block;}
        .f-label .req{color:var(--dan);}
        .f-input{width:100%;height:44px;padding:0 1rem;border:1.5px solid var(--bdr);border-radius:var(--r-xs);font-size:.88rem;color:var(--t1);transition:.2s;background:var(--wh);outline:none;}
        .f-input:focus{border-color:var(--pri);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
        .f-input-lg{height:52px;font-size:1rem;font-weight:600;}
        .f-textarea{height:auto;min-height:120px;padding:.75rem 1rem;resize:vertical;}
        .f-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center;}
        .f-help{font-size:.73rem;color:var(--t4);margin-top:.3rem;}
        .f-err{font-size:.73rem;color:var(--dan);margin-top:.3rem;}
        .f-group{margin-bottom:1rem;}
        .f-prefix{position:relative;}.f-prefix-text{position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:1.1rem;font-weight:700;color:var(--t4);}.f-prefix .f-input{padding-left:2.25rem;}
        .f-changed{border-left:4px solid var(--pri) !important;}

        .btn-s{height:44px;padding:0 1.5rem;border-radius:var(--r-xs);font-weight:600;font-size:.88rem;display:inline-flex;align-items:center;justify-content:center;gap:.4rem;border:none;cursor:pointer;transition:.15s;text-decoration:none;}
        .btn-s:active{transform:scale(.98);}
        .btn-pri{background:var(--pri);color:#fff;}.btn-pri:hover{background:var(--pri-dk);color:#fff;}
        .btn-out{background:none;border:1.5px solid var(--bdr);color:var(--t2);}.btn-out:hover{border-color:var(--pri);color:var(--pri);}
        .btn-dan{background:none;border:1.5px solid var(--dan);color:var(--dan);}.btn-dan:hover{background:#fef2f2;}
        .btn-suc{background:var(--suc);color:#fff;}.btn-suc:hover{background:#059669;color:#fff;}
        .btn-ghost{background:none;border:none;color:var(--t3);padding:0 .5rem;height:36px;font-size:.85rem;}.btn-ghost:hover{color:var(--pri);background:var(--pri-lt);}

        /* Header preview */
        .edit-header{display:flex;align-items:center;gap:1rem;flex-wrap:wrap;}
        .edit-thumb{width:64px;height:64px;border-radius:var(--r-sm);overflow:hidden;background:#f1f5f9;flex-shrink:0;}
        .edit-thumb img{width:100%;height:100%;object-fit:cover;}
        .edit-meta{font-size:.78rem;color:var(--t4);display:flex;gap:.6rem;margin-top:.25rem;flex-wrap:wrap;}
        .edit-meta span{display:flex;align-items:center;gap:.2rem;}
        .status-pill{display:inline-flex;align-items:center;gap:.25rem;padding:.2rem .55rem;border-radius:99px;font-size:.7rem;font-weight:700;}
        .st-active{background:var(--suc-lt);color:#16a34a;}.st-pending{background:var(--warn-lt);color:#d97706;}.st-paused{background:#f1f5f9;color:var(--t3);}

        /* Status selector */
        .status-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.5rem;}
        .status-card{padding:.75rem;border:2px solid var(--bdr);border-radius:var(--r-sm);cursor:pointer;text-align:center;transition:.2s;}
        .status-card:hover{border-color:var(--pri);}
        .status-card.sel{border-color:var(--pri);background:var(--pri-lt);}
        .status-card i{font-size:1.2rem;color:var(--pri);margin-bottom:.3rem;display:block;}
        .status-card .sc-name{font-size:.82rem;font-weight:600;color:var(--t1);}
        .status-card .sc-desc{font-size:.65rem;color:var(--t4);margin-top:.1rem;}

        /* Toggle group */
        .toggle-group{display:flex;gap:0;border:1.5px solid var(--bdr);border-radius:var(--r-xs);overflow:hidden;}
        .toggle-opt{flex:1;padding:.45rem;text-align:center;font-size:.78rem;font-weight:600;color:var(--t3);cursor:pointer;transition:.15s;border-right:1px solid var(--bdr);}
        .toggle-opt:last-child{border-right:none;}.toggle-opt:hover{background:var(--pri-lt);}.toggle-opt.sel{background:var(--pri);color:#fff;}

        /* Accordion */
        .acc-header{display:flex;align-items:center;justify-content:space-between;padding:.85rem 0;cursor:pointer;border-top:1px solid var(--bdr);margin-top:.5rem;}
        .acc-header:first-child{border-top:none;margin-top:0;}
        .acc-title{font-size:.92rem;font-weight:600;color:var(--t1);display:flex;align-items:center;gap:.5rem;}
        .acc-badge{font-size:.68rem;font-weight:700;padding:.15rem .45rem;border-radius:99px;}
        .acc-badge-warn{background:var(--warn-lt);color:#d97706;}
        .acc-chev{transition:transform .2s;color:var(--t4);font-size:1rem;}
        .acc-chev.open{transform:rotate(180deg);}
        .acc-body{overflow:hidden;max-height:0;transition:max-height .35s ease,padding .35s;padding:0;}
        .acc-body.open{max-height:2000px;padding:.75rem 0 .5rem;}

        /* Photo grid */
        .photo-grid-edit{display:grid;grid-template-columns:repeat(4,1fr);gap:.6rem;}
        .photo-tile{position:relative;aspect-ratio:1;border-radius:var(--r-sm);overflow:hidden;background:#f1f5f9;}
        .photo-tile img{width:100%;height:100%;object-fit:cover;}
        .photo-tile .pt-badge{position:absolute;top:5px;left:5px;background:var(--pri);color:#fff;font-size:.6rem;font-weight:700;padding:.12rem .4rem;border-radius:99px;}
        .photo-tile .pt-del{position:absolute;top:5px;right:5px;width:24px;height:24px;border-radius:50%;background:rgba(0,0,0,.6);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.65rem;opacity:0;transition:.2s;}
        .photo-tile:hover .pt-del{opacity:1;}
        .photo-add{aspect-ratio:1;border:2px dashed var(--bdr);border-radius:var(--r-sm);display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:.2s;color:var(--t4);font-size:.78rem;}
        .photo-add:hover{border-color:var(--pri);background:var(--pri-lt);color:var(--pri);}
        .photo-add i{font-size:1.3rem;margin-bottom:.2rem;}

        /* Actions card */
        .action-item{display:flex;align-items:center;gap:.6rem;padding:.6rem .75rem;border-radius:var(--r-xs);cursor:pointer;transition:.15s;font-size:.85rem;font-weight:500;color:var(--t2);text-decoration:none;border:none;background:none;width:100%;text-align:left;}
        .action-item:hover{background:var(--pri-lt);color:var(--pri);}
        .action-item i{font-size:1rem;width:20px;text-align:center;}
        .action-item.danger{color:var(--dan);}.action-item.danger:hover{background:#fef2f2;color:var(--dan);}
        .action-divider{height:1px;background:var(--bdr);margin:.3rem 0;}

        /* Sticky save bar */
        .save-bar{position:fixed;bottom:0;left:0;right:0;z-index:200;background:var(--wh);border-top:1px solid var(--bdr);box-shadow:0 -4px 16px rgba(0,0,0,.08);padding:.75rem 0;transform:translateY(100%);transition:transform .3s;display:flex;align-items:center;justify-content:center;}
        .save-bar.show{transform:translateY(0);}
        .save-bar-inner{display:flex;align-items:center;gap:1rem;max-width:900px;width:100%;padding:0 1rem;}
        .save-bar-text{flex:1;font-size:.85rem;color:var(--t3);}
        .save-bar-text strong{color:var(--warn);}

        /* Delete modal */
        .modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);z-index:1000;display:none;align-items:center;justify-content:center;}
        .modal-bg.open{display:flex;}
        .modal-box{background:var(--wh);border-radius:var(--r);max-width:420px;width:90%;padding:2rem;text-align:center;}

        .toast-wrap{position:fixed;top:20px;right:20px;z-index:3000;}
        .toast-msg{background:var(--t1);color:#fff;padding:.65rem 1.1rem;border-radius:var(--r-xs);font-size:.85rem;font-weight:500;box-shadow:0 12px 32px rgba(0,0,0,.1);animation:toastIn .3s ease,toastOut .3s ease 2.7s forwards;}
        @keyframes toastIn{from{opacity:0;transform:translateX(30px);}to{opacity:1;transform:translateX(0);}}
        @keyframes toastOut{from{opacity:1;}to{opacity:0;}}

        .sp-foot{background:var(--dk);color:rgba(255,255,255,.6);padding:2rem 0 1rem;margin-top:3rem;}
        .sp-foot a{color:rgba(255,255,255,.5);text-decoration:none;font-size:.83rem;}

        @media(max-width:991.98px){.perf-col{display:none;}}
        @media(max-width:767.98px){.status-grid{grid-template-columns:repeat(2,1fr);}.photo-grid-edit{grid-template-columns:repeat(3,1fr);}.edit-header{flex-direction:column;align-items:flex-start;}}
    </style>
</head>
<body>
    <div class="toast-wrap" id="toastWrap"></div>

    {{-- Delete modal --}}
    <div class="modal-bg" id="delModal">
        <div class="modal-box">
            <div style="font-size:2.5rem;color:var(--dan);"><i class="bi bi-exclamation-triangle"></i></div>
            <h3 style="font-size:1.1rem;font-weight:700;color:var(--dk);margin:.5rem 0;">Dzēst sludinājumu?</h3>
            <p style="font-size:.85rem;color:var(--t3);margin-bottom:1rem;">Šī darbība ir neatgriezeniska.</p>
            <div style="display:flex;gap:.5rem;justify-content:center;">
                <button class="btn-s btn-out" onclick="document.getElementById('delModal').classList.remove('open')">Atcelt</button>
                <form action="{{ route('ads.destroy', $ad->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-s" style="background:var(--dan);color:#fff;"><i class="bi bi-trash3 me-1"></i> Dzēst</button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.navbar')

    @php
        $images = $ad->getAllImages();
        $imgUrl = fn($img) => str_starts_with($img, 'http') ? $img : route('ad.image', basename($img));
    @endphp

    <div class="container" style="margin-top:.5rem;padding-bottom:5rem;">
        {{-- Breadcrumb --}}
        <div class="bc">
            <a href="{{ route('ads.index') }}">Mani sludinājumi</a>
            <i class="bi bi-chevron-right sep"></i>
            <span class="cur">{{ Str::limit($ad->name, 35) }}</span>
            <i class="bi bi-chevron-right sep"></i>
            <span class="cur">Rediģēt</span>
        </div>

        <div class="row g-4">
            {{-- ===== MAIN COLUMN ===== --}}
            <div class="col-lg-8">

                {{-- Page header --}}
                <div class="s-card" style="padding:1.25rem;">
                    <div class="edit-header">
                        <div class="edit-thumb">
                            @if($ad->feature_image)
                                <img src="{{ $imgUrl($ad->feature_image) }}" alt="">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100"><i class="bi bi-image" style="color:var(--t5);font-size:1.3rem;"></i></div>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:1.1rem;font-weight:700;color:var(--dk);display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                                {{ Str::limit($ad->name, 40) }}
                                <span class="status-pill {{ $ad->published ? 'st-active' : 'st-pending' }}">
                                    <i class="bi {{ $ad->published ? 'bi-check-circle-fill' : 'bi-clock' }}"></i>
                                    {{ $ad->published ? 'Aktīvs' : 'Gaida' }}
                                </span>
                            </div>
                            <div class="edit-meta">
                                <span><i class="bi bi-clock"></i> {{ $ad->created_at->diffForHumans() }}</span>
                                @if($ad->listing_location)<span><i class="bi bi-geo-alt-fill"></i> {{ $ad->listing_location }}</span>@endif
                                <span><i class="bi bi-tag-fill"></i> {{ $ad->category->name ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== QUICK EDIT ===== --}}
                <form action="{{ route('ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
                    @csrf @method('PUT')

                    <div class="s-card">
                        <div class="s-title"><i class="bi bi-lightning-fill"></i> Ātrie labojumi</div>
                        <div class="s-sub">Biežāk labotie lauki — mainiet un saglabājiet uzreiz</div>

                        @if($errors->any())
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-xs);padding:.75rem 1rem;margin-bottom:1rem;">
                                @foreach($errors->all() as $err)
                                    <div style="font-size:.82rem;color:#dc2626;">• {{ $err }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="f-group">
                                    <label class="f-label">Nosaukums <span class="req">*</span></label>
                                    <input type="text" name="name" class="f-input f-input-lg" value="{{ $ad->name }}" data-orig="{{ $ad->name }}" oninput="trackChange(this)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="f-group">
                                    <label class="f-label">Cena <span class="req">*</span></label>
                                    <div class="f-prefix">
                                        <span class="f-prefix-text">&euro;</span>
                                        <input type="number" name="price" class="f-input f-input-lg" value="{{ $ad->price }}" step="0.01" data-orig="{{ $ad->price }}" oninput="trackChange(this)">
                                    </div>
                                    <div class="toggle-group" style="margin-top:.5rem;">
                                        @foreach(['Sarunājama','Fiksēta','Bezmaksas'] as $ps)
                                            <div class="toggle-opt {{ $ad->price_status === $ps ? 'sel' : '' }}" onclick="selToggle(this,'price_status','{{ $ps }}')">{{ $ps }}</div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="price_status" id="price_status" value="{{ $ad->price_status }}">
                                </div>
                            </div>
                        </div>

                        {{-- Status selector --}}
                        <div class="f-group" style="margin-top:.5rem;">
                            <label class="f-label">Statuss</label>
                            <div class="status-grid">
                                <div class="status-card {{ $ad->published ? 'sel' : '' }}" onclick="selStatus(this,1)">
                                    <i class="bi bi-check-circle-fill" style="color:var(--suc);"></i>
                                    <div class="sc-name">Aktīvs</div>
                                    <div class="sc-desc">Redzams visiem</div>
                                </div>
                                <div class="status-card" onclick="selStatus(this,1)">
                                    <i class="bi bi-bookmark-star" style="color:var(--warn);"></i>
                                    <div class="sc-name">Rezervēts</div>
                                    <div class="sc-desc">Pircējs atrasts</div>
                                </div>
                                <div class="status-card {{ !$ad->published ? 'sel' : '' }}" onclick="selStatus(this,0)">
                                    <i class="bi bi-pause-circle" style="color:var(--t4);"></i>
                                    <div class="sc-name">Pauzēts</div>
                                    <div class="sc-desc">Uz laiku paslēpts</div>
                                </div>
                                <div class="status-card" onclick="selStatus(this,0)">
                                    <i class="bi bi-bag-check" style="color:var(--dk);"></i>
                                    <div class="sc-name">Pārdots</div>
                                    <div class="sc-desc">Arhivēt</div>
                                </div>
                            </div>
                            <input type="hidden" name="published" id="publishedField" value="{{ $ad->published }}">
                        </div>
                    </div>

                    {{-- ===== ACCORDION SECTIONS ===== --}}
                    <div class="s-card" style="padding:1rem 1.5rem;">

                        {{-- Photos accordion --}}
                        <div class="acc-header" onclick="toggleAcc('accPhotos',this)">
                            <div class="acc-title"><i class="bi bi-images" style="color:var(--pri);"></i> Fotogrāfijas ({{ count($images) }})</div>
                            <i class="bi bi-chevron-down acc-chev"></i>
                        </div>
                        <div class="acc-body" id="accPhotos">
                            <div class="photo-grid-edit">
                                @foreach($images as $i => $img)
                                    <div class="photo-tile">
                                        <img src="{{ $imgUrl($img) }}" alt="Foto {{ $i+1 }}">
                                        @if($i === 0)<span class="pt-badge">Galvenā</span>@endif
                                    </div>
                                @endforeach
                                @if(count($images) < 12)
                                    <div class="photo-add" onclick="document.getElementById('newPhotos').click()">
                                        <i class="bi bi-plus-lg"></i>
                                        Pievienot
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="ad_images[]" id="newPhotos" accept="image/*" multiple style="display:none;" onchange="showToast(this.files.length + ' jauns foto pievienots')">
                            <div class="f-help" style="margin-top:.5rem;">Velc vai noklikšķini lai pievienotu. Līdz 12 fotogrāfijām.</div>
                        </div>

                        {{-- Description accordion --}}
                        <div class="acc-header" onclick="toggleAcc('accDesc',this)">
                            <div class="acc-title"><i class="bi bi-text-paragraph" style="color:var(--pri);"></i> Apraksts</div>
                            <i class="bi bi-chevron-down acc-chev"></i>
                        </div>
                        <div class="acc-body" id="accDesc">
                            <div class="f-group">
                                <label class="f-label">Apraksts <span class="req">*</span></label>
                                <textarea name="description" class="f-input f-textarea" data-orig="{{ $ad->description }}" oninput="trackChange(this)">{{ $ad->description }}</textarea>
                            </div>
                        </div>

                        {{-- Category accordion --}}
                        <div class="acc-header" onclick="toggleAcc('accCat',this)">
                            <div class="acc-title"><i class="bi bi-tag" style="color:var(--pri);"></i> Kategorija — {{ $ad->category->name ?? '' }}</div>
                            <i class="bi bi-chevron-down acc-chev"></i>
                        </div>
                        <div class="acc-body" id="accCat">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="f-label">Kategorija</label>
                                    <select name="category_id" id="category_id" class="f-input f-select" onchange="loadSubs(this.value)">
                                        <option value="">Izvēlies</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $ad->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="f-label">Apakškategorija</label>
                                    <select name="subcategory_id" id="subcategory_id" class="f-input f-select">
                                        <option value="">Izvēlies</option>
                                        @if($ad->category)
                                            @foreach($ad->category->subcategories as $sub)
                                                <option value="{{ $sub->id }}" {{ $ad->subcategory_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="f-label">Precizēt</label>
                                    <select name="childcategory_id" id="childcategory_id" class="f-input f-select">
                                        <option value="">Izvēlies</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Condition accordion --}}
                        <div class="acc-header" onclick="toggleAcc('accCond',this)">
                            <div class="acc-title"><i class="bi bi-check2-circle" style="color:var(--pri);"></i> Stāvoklis</div>
                            <i class="bi bi-chevron-down acc-chev"></i>
                        </div>
                        <div class="acc-body" id="accCond">
                            <select name="product_condition" class="f-input f-select">
                                @foreach(['new'=>'Jauns','likenew'=>'Kā jauns','used'=>'Lietots','parts'=>'Rezerves daļām'] as $val => $label)
                                    <option value="{{ $val }}" {{ $ad->product_condition === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Location accordion --}}
                        <div class="acc-header" onclick="toggleAcc('accLoc',this)">
                            <div class="acc-title"><i class="bi bi-geo-alt" style="color:var(--pri);"></i> Atrašanās vieta — {{ $ad->listing_location ?? 'Nav norādīta' }}</div>
                            <i class="bi bi-chevron-down acc-chev"></i>
                        </div>
                        <div class="acc-body" id="accLoc">
                            <div class="f-group">
                                <label class="f-label">Atrašanās vieta</label>
                                <input type="text" name="listing_location" class="f-input" value="{{ $ad->listing_location }}" data-orig="{{ $ad->listing_location }}" oninput="trackChange(this)">
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="f-label">Valsts</label>
                                    <select name="country_id" id="country_id" class="f-input f-select" onchange="loadStates(this.value)">
                                        <option value="">Izvēlies</option>
                                        @foreach(App\Models\Country::all() as $c)
                                            <option value="{{ $c->id }}" {{ $ad->country_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="f-label">Reģions</label>
                                    <select name="state_id" id="state_id" class="f-input f-select">
                                        <option value="">Izvēlies</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="f-label">Pilsēta</label>
                                    <select name="city_id" id="city_id" class="f-input f-select">
                                        <option value="">Izvēlies</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Contact accordion --}}
                        <div class="acc-header" onclick="toggleAcc('accContact',this)">
                            <div class="acc-title"><i class="bi bi-telephone" style="color:var(--pri);"></i> Kontakti</div>
                            <i class="bi bi-chevron-down acc-chev"></i>
                        </div>
                        <div class="acc-body" id="accContact">
                            <div class="f-group">
                                <label class="f-label">Tālrunis</label>
                                <input type="tel" name="phone_number" class="f-input" value="{{ $ad->phone_number }}">
                            </div>
                            <div class="f-group">
                                <label class="f-label">Video saite</label>
                                <input type="url" name="link" class="f-input" value="{{ $ad->link }}" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>

                    {{-- Hidden submit for save bar --}}
                    <button type="submit" id="formSubmit" style="display:none;"></button>
                </form>
            </div>

            {{-- ===== RIGHT COLUMN ===== --}}
            <div class="col-lg-4">
                {{-- Actions --}}
                <div class="s-card perf-col" style="padding:1.25rem;">
                    <div class="s-title" style="margin-bottom:.75rem;"><i class="bi bi-lightning-charge"></i> Darbības</div>
                    <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" target="_blank" class="action-item">
                        <i class="bi bi-eye"></i> Skatīt sludinājumu
                    </a>
                    <a href="{{ url('/ads/create') }}" class="action-item">
                        <i class="bi bi-copy"></i> Dublēt sludinājumu
                    </a>
                    <button class="action-item" onclick="shareAd()">
                        <i class="bi bi-share"></i> Dalīties
                    </button>
                    <div class="action-divider"></div>
                    <button class="action-item danger" onclick="document.getElementById('delModal').classList.add('open')">
                        <i class="bi bi-trash3"></i> Dzēst sludinājumu
                    </button>
                </div>

                {{-- Tips --}}
                <div class="s-card perf-col" style="padding:1.25rem;">
                    <div class="s-title" style="margin-bottom:.75rem;"><i class="bi bi-lightbulb"></i> Ieteikumi</div>
                    @if(count($images) < 3)
                        <div style="padding:.6rem .75rem;background:#fffbeb;border:1px solid #fde68a;border-radius:var(--r-xs);margin-bottom:.5rem;">
                            <div style="font-size:.82rem;font-weight:600;color:#92400e;"><i class="bi bi-camera me-1"></i> Pievieno vairāk foto</div>
                            <div style="font-size:.72rem;color:#a16207;">Sludinājumi ar 5+ foto saņem 2× vairāk ziņu</div>
                        </div>
                    @endif
                    @if(strlen($ad->description ?? '') < 50)
                        <div style="padding:.6rem .75rem;background:var(--pri-lt);border:1px solid #bfdbfe;border-radius:var(--r-xs);margin-bottom:.5rem;">
                            <div style="font-size:.82rem;font-weight:600;color:var(--pri-dk);"><i class="bi bi-text-paragraph me-1"></i> Uzlabo aprakstu</div>
                            <div style="font-size:.72rem;color:#1e40af;">Detalizēts apraksts palīdz pārdot ātrāk</div>
                        </div>
                    @endif
                    @if(count($images) >= 3 && strlen($ad->description ?? '') >= 50)
                        <div style="padding:.6rem .75rem;background:var(--suc-lt);border:1px solid #a7f3d0;border-radius:var(--r-xs);">
                            <div style="font-size:.82rem;font-weight:600;color:#065f46;"><i class="bi bi-check-circle-fill me-1"></i> Lielisks sludinājums!</div>
                            <div style="font-size:.72rem;color:#166534;">Tavs sludinājums ir labi noformēts</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky save bar --}}
    <div class="save-bar" id="saveBar">
        <div class="save-bar-inner">
            <div class="save-bar-text"><strong><i class="bi bi-exclamation-circle me-1"></i> Nesaglabātas izmaiņas</strong></div>
            <button class="btn-s btn-out" onclick="revertAll()" style="height:38px;">Atcelt</button>
            <button class="btn-s btn-pri" onclick="document.getElementById('formSubmit').click()" style="height:38px;">
                <i class="bi bi-check-lg"></i> Saglabāt izmaiņas
            </button>
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
    var changedFields = new Set();

    // Accordion
    function toggleAcc(id, header) {
        var body = document.getElementById(id);
        var chev = header.querySelector('.acc-chev');
        body.classList.toggle('open');
        chev.classList.toggle('open');
    }

    // Change tracking
    function trackChange(el) {
        var orig = el.dataset.orig || '';
        var cur = el.value || '';
        if (cur !== orig) {
            el.classList.add('f-changed');
            changedFields.add(el.name);
        } else {
            el.classList.remove('f-changed');
            changedFields.delete(el.name);
        }
        document.getElementById('saveBar').classList.toggle('show', changedFields.size > 0);
    }

    function revertAll() {
        document.querySelectorAll('[data-orig]').forEach(function(el) {
            el.value = el.dataset.orig;
            el.classList.remove('f-changed');
        });
        changedFields.clear();
        document.getElementById('saveBar').classList.remove('show');
    }

    // Status
    function selStatus(el, val) {
        document.querySelectorAll('.status-card').forEach(function(c){ c.classList.remove('sel'); });
        el.classList.add('sel');
        document.getElementById('publishedField').value = val;
        changedFields.add('status');
        document.getElementById('saveBar').classList.add('show');
    }

    // Toggle
    function selToggle(el, field, val) {
        el.parentElement.querySelectorAll('.toggle-opt').forEach(function(o){ o.classList.remove('sel'); });
        el.classList.add('sel');
        document.getElementById(field).value = val;
        changedFields.add(field);
        document.getElementById('saveBar').classList.add('show');
    }

    // Category cascading
    function loadSubs(catId) {
        if (!catId) return;
        $.get('/get-subcategories/' + catId, function(data) {
            var sel = document.getElementById('subcategory_id');
            sel.innerHTML = '<option value="">Izvēlies</option>';
            data.forEach(function(s){ sel.innerHTML += '<option value="'+s.id+'">'+s.name+'</option>'; });
        });
    }
    function loadStates(countryId) {
        if (!countryId) return;
        $.get('/get-states/' + countryId, function(data) {
            var sel = document.getElementById('state_id');
            sel.innerHTML = '<option value="">Izvēlies</option>';
            data.forEach(function(s){ sel.innerHTML += '<option value="'+s.id+'">'+s.name+'</option>'; });
        });
    }

    // Share
    function shareAd() {
        var url = '{{ route("product.view", ["id" => $ad->id, "slug" => $ad->slug]) }}';
        if (navigator.share) { navigator.share({url: url}); }
        else { navigator.clipboard?.writeText(url); showToast('Saite nokopēta!'); }
    }

    // Toast
    function showToast(msg) {
        var el = document.createElement('div'); el.className = 'toast-msg'; el.textContent = msg;
        document.getElementById('toastWrap').appendChild(el);
        setTimeout(function(){ el.remove(); }, 3200);
    }

    // Keyboard shortcut Ctrl+S
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (changedFields.size > 0) document.getElementById('formSubmit').click();
        }
    });

    // Warn on leave
    window.addEventListener('beforeunload', function(e) {
        if (changedFields.size > 0) { e.preventDefault(); e.returnValue = ''; }
    });

    // Submit loading
    document.getElementById('editForm').addEventListener('submit', function() {
        var btn = document.querySelector('.save-bar .btn-pri');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saglabā...';
        btn.disabled = true;
    });
    </script>
</body>
</html>
