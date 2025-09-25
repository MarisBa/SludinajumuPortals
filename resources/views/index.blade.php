@php
    use Illuminate\Support\Str;

    // Demo dati (lai redzētu dizainu bez DB)
    $questions = collect([
        (object)[
            'title' => 'Kas ir populārākās apskates vietas Rīgā?',
            'body' => 'Vēlos uzzināt, kuras vietas obligāti jāapmeklē, ja ierodos uz pāris dienām Rīgā.',
            'category' => (object)['name' => 'Ceļošana']
        ],
        (object)[
            'title' => 'Kādas ir tradicionālās latviešu ēdienu receptes?',
            'body' => 'Vai kāds var padalīties ar rupjmaizes zupas vai sklandraušu recepti?',
            'category' => (object)['name' => 'Ēdieni']
        ],
        (object)[
            'title' => 'Kur var apskatīt skaistākās dabas takas Latvijā?',
            'body' => 'Meklēju ieteikumus vieglām pārgājienu takām ģimenei ar bērniem.',
            'category' => (object)['name' => 'Daba']
        ],
        (object)[
            'title' => 'Kādi ir populārākie Latvijas svētki un tradīcijas?',
            'body' => 'Gribu uzzināt vairāk par Jāņiem un citiem svētkiem.',
            'category' => (object)['name' => 'Kultūra']
        ],
        (object)[
            'title' => 'Kāds ir sabiedriskais transports Rīgā?',
            'body' => 'Kā visērtāk pārvietoties pa galvaspilsētu – tramvajs, trolejbuss vai autobuss?',
            'category' => (object)['name' => 'Transports']
        ],
        (object)[
            'title' => 'Kur Latvijā vislabāk slēpot?',
            'body' => 'Vai ir kādas labas kalnu slēpošanas trases ģimenei?',
            'category' => (object)['name' => 'Sports']
        ],
    ]);
@endphp

@extends('layouts.app')

@section('content')
<!-- Hero Carousel -->
<div class="container-fluid px-0">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('img/latvija1.jpg') }}" class="d-block w-100" alt="Latvija">

                <div class="carousel-caption d-none d-md-block">
                    <h2 class="fw-bold">Izzini Latviju</h2>
                    <p>Uzdod jautājumus un dalies ar zināšanām par Latviju</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/latvija2.jpg') }}" class="d-block w-100" alt="Daba">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Daba un kultūra</h2>
                    <p>Atklāj Latvijas skaistākās vietas</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/latvija3.jpg') }}" class="d-block w-100" alt="Pilsētas">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Pilsētas un vēsture</h2>
                    <p>Dalies ar savu pieredzi un zināšanām</p>
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

<!-- Latest Questions -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Jaunākie Jautājumi</h1>
        <a href="#" class="btn btn-outline-primary">Skatīt visus</a>
    </div>

    @if($questions->count())
        <div class="row g-3">
            @foreach($questions->take(6) as $question)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="#" class="text-decoration-none">
                                    {{ Str::limit($question->title, 60) }}
                                </a>
                            </h5>
                            <p class="card-text text-muted mb-2">
                                {{ Str::limit($question->body, 100) }}
                            </p>
                            <small class="text-secondary">
                                Kategorija: {{ $question->category->name ?? 'Vispārīgi' }}
                            </small>
                        </div>
                        <div class="card-footer text-end">
                            <a href="#" class="btn btn-sm btn-primary">
                                Apskatīt
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Pagaidām nav jautājumu.</p>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Carousel(document.getElementById('mainCarousel'), {
            interval: 3000,
            wrap: true,
            pause: 'hover'
        });
    });
</script>
@endsection

<style>
    /* Hero Carousel images */
    #mainCarousel .carousel-item img {
        height: 500px;       /* izvēlies vajadzīgo augstumu, piemēram, 400px, 500px vai 600px */
        object-fit: cover;   /* lai bilde neaizstiepjas, bet piegriežas */
    }
</style>
s
