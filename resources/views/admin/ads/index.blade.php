@extends('admin.layout')

@section('title', 'Sludinājumi')

@section('content')
    <div class="panel mb-3">
        <div class="panel-body">
            <form method="GET" action="{{ route('admin.ads.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label small text-muted mb-1">Meklēt</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Sludinājuma nosaukums...">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small text-muted mb-1">Kategorija</label>
                    <select name="category" class="form-select">
                        <option value="">Visas kategorijas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('category') === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Statuss</label>
                    <select name="status" class="form-select">
                        <option value="">Visi</option>
                        <option value="published" @selected(request('status')==='published')>Publicēti</option>
                        <option value="hidden" @selected(request('status')==='hidden')>Paslēpti</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Atlasīt</button>
                </div>
                @if(request('search') || request('category') || request('status'))
                    <div class="col-12">
                        <a href="{{ route('admin.ads.index') }}" class="btn btn-sm btn-link">Notīrīt filtrus</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2><i class="bi bi-megaphone me-1"></i> Sludinājumu saraksts</h2>
            <span class="text-muted small">Kopā: {{ $ads->total() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Attēls</th>
                        <th>Nosaukums</th>
                        <th>Cena</th>
                        <th>Kategorija</th>
                        <th>Lietotājs</th>
                        <th>Statuss</th>
                        <th>Datums</th>
                        <th class="text-end">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ads as $ad)
                        <tr>
                            <td class="text-muted">#{{ $ad->id }}</td>
                            <td>
                                @if($ad->feature_image)
                                    <img class="thumb" src="{{ asset('storage/' . ltrim(str_replace('public/', '', $ad->feature_image), '/')) }}" alt="">
                                @else
                                    <span class="thumb thumb-fallback"><i class="bi bi-image"></i></span>
                                @endif
                            </td>
                            <td class="fw-semibold text-truncate" style="max-width:240px;">{{ $ad->name }}</td>
                            <td>{{ number_format((float) $ad->price, 2, ',', ' ') }} €</td>
                            <td>{{ $ad->category->name ?? '—' }}</td>
                            <td>{{ $ad->user->name ?? '—' }}</td>
                            <td>
                                @if($ad->published)
                                    <span class="badge-soft bg-suc">Publicēts</span>
                                @else
                                    <span class="badge-soft bg-warn">Paslēpts</span>
                                @endif
                            </td>
                            <td>{{ optional($ad->created_at)->format('d.m.Y') }}</td>
                            <td class="text-end">
                                <a href="{{ url('/product-detail/' . $ad->id . '/' . $ad->slug) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-secondary btn-icon" title="Skatīt">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.ads.toggle', $ad) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-icon"
                                            title="{{ $ad->published ? 'Paslēpt' : 'Publicēt' }}">
                                        <i class="bi {{ $ad->published ? 'bi-eye-slash' : 'bi-eye-fill' }}"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger btn-icon"
                                        data-bs-toggle="modal" data-bs-target="#delAd{{ $ad->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="modal fade" id="delAd{{ $ad->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Dzēst sludinājumu?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                Vai tiešām vēlies dzēst sludinājumu <strong>{{ $ad->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Atcelt</button>
                                                <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Dzēst</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="empty-state">Nav atbilstošu sludinājumu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ads->hasPages())
            <div class="panel-body">
                {{ $ads->links() }}
            </div>
        @endif
    </div>
@endsection
