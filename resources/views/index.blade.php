@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')

<div class="container-fluid px-0">
    <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner" style="height: 500px;"> {{-- Palielināts augstums, lai labāk redzētu ainavas --}}
            <div class="carousel-item active">
                <img src="/img/latvija1.jpg" class="d-block w-100 h-100 object-fit-cover object-position-bottom" alt="Latvijas ainava 1 - Rudens meži no putna lidojuma">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                    <h1 class="display-5 fw-bold">Atklāj Latvijas Neparastās Vietas</h1>
                    <p class="fs-5">Dalies ar saviem atklājumiem un ceļojumu iespaidiem.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="/img/latvija2.jpg" class="d-block w-100 h-100 object-fit-cover object-position-bottom" alt="Latvijas ainava 2 - Baltijas jūras piekraste">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                    <h1 class="display-5 fw-bold">Daba, Atpūta un Piedzīvojumi</h1>
                    <p class="fs-5">Ieteikumi, kur atpūsties pie dabas un ko darīt aktīvi.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="/img/latvija3.jpg" class="d-block w-100 h-100 object-fit-cover object-position-bottom" alt="Latvijas ainava 3 - Rīgas panorāma saullēktā">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                    <h1 class="display-5 fw-bold">Rīgas Pulss: Dzīve un Kultūra Galvaspilsētā</h1>
                    <p class="fs-5">Jautājumi par pilsētas dzīvi, pasākumiem un vēsturi.</p>
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
        <h2>Jaunākās Diskusijas par Latviju</h2>
        <a href="{{ route('ads.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Uzsākt Jaunu Diskusiju
        </a>
    </div>

    @if($ads->count())
        <div class="list-group list-group-flush">
            @foreach($ads as $ad)
                <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-start border-bottom-light">
                    
                    <div class="d-flex w-100 me-3">
                        <div class="icon-container me-3 text-center text-primary" style="min-width: 40px;">
                            <i class="bi bi-question-circle-fill fs-4"></i>
                        </div>
                        
                        <div>
                            <h5 class="mb-1 text-dark">{{ Str::limit($ad->name, 80) }}</h5>
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-tag me-1"></i> Kategorija: <strong>{{ $ad->category->name ?? 'Vispārīgi' }}</strong>
                            </small>
                            <p class="mb-1 d-none d-sm-block">{{ Str::limit($ad->description ?? $ad->address, 120) }}</p>
                            
                            <small class="text-secondary">
                                Pēdējā aktivitāte: {{ $ad->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>

                    <div class="d-flex flex-column text-end ms-auto">
                        <span class="badge bg-success text-white mb-1">{{ rand(1, 50) }}</span> 
                        <small class="text-muted">Atbildes</small>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-5 mb-5">
            <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary btn-lg">Apskatīt Visas Diskusijas un Tēmas</a>
        </div>
        
    @else
        <div class="alert alert-info text-center my-5" role="alert">
            Šobrīd forumā nav jaunu tēmu. Sāciet pirmo diskusiju!
        </div>
    @endif
</div>

<div class="container py-4 border-top">
    <div class="row text-center">
        <div class="col-4">
            <h3 class="fw-bold text-primary">{{ $ads->count() }}</h3>
            <p class="text-muted">Aktīvās Diskusijas</p>
        </div>
        <div class="col-4">
            <h3 class="fw-bold text-primary">{{ rand(100, 500) }}</h3>
            <p class="text-muted">Reģistrētie Lietotāji</p>
        </div>
        <div class="col-4">
            <h3 class="fw-bold text-primary">{{ rand(500, 9000) }}</h3>
            <p class="text-muted">Kopējais Postu Skaits</p>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Galvenā karuseļa inicializācija
        new bootstrap.Carousel(document.getElementById('mainCarousel'), {
            interval: 5000, // Karuselis mainās lēnāk, lai labāk varētu izlasīt virsrakstu
            wrap: true,
            pause: 'hover'
        });
    });
</script>