@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')

<style>
/* ----------------------------------- */
/* ---------- KRĀSU PALETE ---------- */
/* ----------------------------------- */
:root {
    --bs-main-blue: #0057B7; /* Galvenā zilā krāsa (Latvijas karoga tonis) */
    --bs-dark-blue: #00449e; /* Tumšā zilā (hover) */
    --bs-primary-light: #e6f0ff; /* Ļoti gaiši zils fons */
    --bs-success: #28a745; /* Zaļš (atbildes/badge) */
    --bs-text-color: #212529;
    --bs-light-gray: #f8f9fa;
}

/* ---------- Global Styles ---------- */
body {
    background-color: var(--bs-light-gray);
    font-family: 'Inter', 'Segoe UI', sans-serif;
    color: var(--bs-text-color);
}
a {
    text-decoration: none;
}
a:hover {
    color: var(--bs-dark-blue);
}
.btn-primary {
    background-color: var(--bs-main-blue);
    border: none;
    transition: background 0.3s ease, transform 0.2s ease;
}
.btn-primary:hover {
    background-color: var(--bs-dark-blue);
    transform: translateY(-2px);
}
.text-shadow {
    text-shadow: 0 2px 8px rgba(0,0,0,0.6);
}
.bg-gradient {
    background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
}

/* ---------- Carousel Styles ---------- */
.fixed-carousel-height {
    height: 450px;
}
@media (max-width: 767.98px) {
    .fixed-carousel-height {
        height: 300px;
    }
}
.carousel-item,
.carousel-item img {
    height: 100%;
}
.object-fit-cover {
    object-fit: cover;
}

/* ----------------------------------- */
/* ---------- JAUTĀJUMU BLOKU STILS UN ANIMĀCIJAS ---------- */
/* ----------------------------------- */
.hover-card {
    /* Sākuma stāvoklis animācijai */
    opacity: 0;
    transform: translateY(20px);
    
    background: #ffffff;
    border-radius: 12px; /* Nedaudz maigāki stūri */
    border: 1px solid rgba(0,0,0,0.05); /* Maiga apmale */
    box-shadow: 0 4px 15px rgba(0,0,0,0.08); /* Maiga, taču skaidra ēna */
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94); /* Maiga animācija visām pārejām */
    
    /* Nodrošina animācijas ielādi */
    animation: fadeInUp 0.5s ease forwards;
}

.hover-card:hover {
    transform: translateY(-8px); /* Paceļas augstāk */
    box-shadow: 0 16px 40px rgba(0,0,0,0.18), 0 0 0 4px rgba(0, 87, 183, 0.1); /* Dziļāka ēna un viegls zils gredzens */
}

.hover-card .card-body {
    padding: 1.5rem;
}

.hover-card .card-title {
    color: var(--bs-main-blue); /* Akcentē jautājuma nosaukumu ar galveno zilo */
    font-weight: 700;
    margin-bottom: 0.6rem;
}

.hover-card .card-footer {
    border-top: 1px solid var(--bs-light-gray); /* Gaiša līnija kā atdalītājs */
    background-color: var(--bs-primary-light); /* Ļoti gaiši zils fons, lai piesaistītu uzmanību */
    padding: 0.75rem 1.5rem;
}

/* Atbilžu zīmulis */
.response-badge {
    background-color: var(--bs-success) !important;
    font-weight: 600;
    padding: 0.4em 0.8em;
    border-radius: 50px;
}

/* Animation definition */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<div class="container-fluid px-0">
    <div id="mainCarousel" class="carousel slide carousel-fade fixed-carousel-height" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <div class="carousel-item active position-relative h-100">
                <img src="/img/latvija1.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Latvijas ainava 1">
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
        <a href="{{ route('forum.posts.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Uzsākt Jaunu Diskusiju
        </a>
    </div>

    @if($ads->count())

        {{-- Include forum posts section --}}
        @include('forum_posts._posts_grid', ['posts' => $posts, 'categories' => $categories])


        <div class="text-center mt-5 mb-5">
            <a href="{{ route('forum.posts.index') }}" class="btn btn-outline-secondary btn-lg">
                Apskatīt Visas Diskusijas un Tēmas
            </a>
        </div>

    @else
        <div class="alert alert-info text-center my-5" role="alert">
            Šobrīd forumā nav jaunu tēmu. Sāciet pirmo diskusiju!
        </div>
    @endif
</div>



@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Karuseļa inicializācija
        new bootstrap.Carousel(document.getElementById('mainCarousel'), {
            interval: 5000,
            wrap: true,
            pause: 'hover'
        });

        // SVARĪGA KODA DAĻA: Uzlabota SECĪGA bloku animācija
        document.querySelectorAll('.hover-card').forEach((el, index) => {
            // Animācijas nosaukums (kustība)
            el.style.animationName = 'fadeInUp';
            
            // Animācijas ilgums
            el.style.animationDuration = '0.5s';
            
            // Secīga aizture: 0s, 0.1s, 0.2s, utt.
            el.style.animationDelay = `${index * 0.1}s`;
            
            // Nodrošina, ka animācija paliek beigu stāvoklī (opacity: 1)
            el.style.animationFillMode = 'forwards'; 
        });
    });
</script>
@endsection