@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')

<style>
/* ---------- Global Styles ---------- */
body {
    background-color: #f8f9fa;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    color: #212529;
}
a {
    text-decoration: none;
}
a:hover {
    color: #0057B7;
}
.btn-primary {
    background-color: #0057B7;
    border: none;
}
.btn-primary:hover {
    background-color: #00449e;
}
.text-shadow {
    text-shadow: 0 2px 8px rgba(0,0,0,0.6);
}
.bg-gradient {
    background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
}
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}
.border-bottom-light {
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

/* ---------- Jauna Klase Fiksētam Augstumam ---------- */
.fixed-carousel-height {
    height: 450px; /* Definē fiksētu augstumu desktopā */
}
/* Nodrošina responsīvu korekciju mazākiem ekrāniem */
@media (max-width: 767.98px) {
    .fixed-carousel-height {
        height: 300px; /* Mazāks augstums mobilajās ierīcēs */
    }
}
/* Nodrošina 100% augstumu visiem iekšējiem elementiem */
.carousel-item,
.carousel-item img {
    height: 100%;
}
</style>

<div class="container-fluid px-0">
    <div id="mainCarousel" class="carousel slide carousel-fade fixed-carousel-height" data-bs-ride="carousel">

        <div class="carousel-inner h-100"> {{-- Uzstādīts h-100 --}}
            <div class="carousel-item active position-relative h-100"> {{-- Uzstādīts h-100 --}}
                <img src="/img/latvija1.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Latvijas ainava 1"> {{-- Uzstādīts h-100 --}}
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient"></div>
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-center">
                    <h1 class="display-4 fw-bold text-white text-shadow">Atklāj Latvijas Neparastās Vietas</h1>
                    <p class="lead text-white">Dalies ar saviem atklājumiem un ceļojumu iespaidiem.</p>
                </div>
            </div>

            <div class="carousel-item position-relative h-100">
                <img src="/img/latvija2.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Latvijas ainava 2">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient"></div>
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-center">
                    <h1 class="display-4 fw-bold text-white text-shadow">Daba, Atpūta un Piedzīvojumi</h1>
                    <p class="lead text-white">Ieteikumi, kur atpūsties pie dabas un ko darīt aktīvi.</p>
                </div>
            </div>

            <div class="carousel-item position-relative h-100">
                <img src="/img/latvija3.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Latvijas ainava 3">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient"></div>
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-center">
                    <h1 class="display-4 fw-bold text-white text-shadow">Rīgas Pulss: Dzīve un Kultūra</h1>
                    <p class="lead text-white">Jautājumi par pilsētas dzīvi, pasākumiem un vēsturi.</p>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Iepriekšējais</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Nākamais</span>
        </button>
    </div>
</div>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="fw-bold">Jaunākās Diskusijas par Latviju</h2>
        <a href="{{ route('ads.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Uzsākt Jaunu Diskusiju
        </a>
    </div>

    @if($ads->count())
        <div class="row g-4">
            @foreach($ads as $ad)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <div class="card-body">
                                <h5 class="card-title text-primary fw-bold">{{ Str::limit($ad->name, 80) }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag me-1"></i>{{ $ad->category->name ?? 'Vispārīgi' }}
                                </p>
                                <p class="card-text">{{ Str::limit($ad->description ?? $ad->address, 120) }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                                <small class="text-secondary">
                                    <i class="bi bi-clock me-1"></i>{{ $ad->updated_at->diffForHumans() }}
                                </small>
                                <span class="badge bg-success"><i class="bi bi-chat-left-text me-1"></i>{{ rand(1, 50) }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5 mb-5">
            <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary btn-lg">
                Apskatīt Visas Diskusijas un Tēmas
            </a>
        </div>
    @else
        <div class="alert alert-info text-center my-5" role="alert">
            Šobrīd forumā nav jaunu tēmu. Sāciet pirmo diskusiju!
        </div>
    @endif
</div>

<div class="bg-light py-5 mt-5 border-top">
    <div class="container text-center">
        <div class="row g-4">
            <div class="col-md-4">
                <i class="bi bi-chat-left-text display-5 text-primary mb-2"></i>
                <h3 class="fw-bold text-dark">{{ $ads->count() }}</h3>
                <p class="text-muted mb-0">Aktīvās Diskusijas</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-people display-5 text-success mb-2"></i>
                <h3 class="fw-bold text-dark">{{ rand(100, 500) }}</h3>
                <p class="text-muted mb-0">Reģistrētie Lietotāji</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-pencil-square display-5 text-warning mb-2"></i>
                <h3 class="fw-bold text-dark">{{ rand(500, 9000) }}</h3>
                <p class="text-muted mb-0">Kopējais Postu Skaits</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Carousel(document.getElementById('mainCarousel'), {
            interval: 5000,
            wrap: true,
            pause: 'hover'
        });
    });
</script>
@endsection