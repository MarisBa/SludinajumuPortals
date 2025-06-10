@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<!-- Main Slider Carousel -->
<div class="container-fluid px-0">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/slider/slider1.png" class="d-block w-100" alt="First slide">
            </div>
            <div class="carousel-item">
                <img src="/slider/slider2.png" class="d-block w-100" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img src="/slider/slider3.png" class="d-block w-100" alt="Third slide">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Advertisement Carousel -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Latest Ads</h1>
        <a href="{{ route('ads.index') }}" class="btn btn-outline-primary">View All</a>
    </div>

    @if($ads->count())
        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                @foreach($ads->chunk(4) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row g-3">
                            @foreach($chunk as $ad)
                                <div class="col-md-3 col-6">
                                    <div class="card h-100">
                                        <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                            

                                                <img src="{{ route('ad.image', basename($ad->feature_image)) }}"
                                                    class="card-img-top img-fluid h-100 w-100 object-fit-cover"
                                                    alt="{{ $ad->name }}">
                                            </a>
                                        </div>
                                        <div class="card-footer text-center">
                                            <p class="mb-1">{{ Str::limit($ad->name, 20) }}</p>
                                            <strong class="d-block mb-2">USD {{ $ad->price }}</strong>
                                            <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="btn btn-primary btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @else
        <p class="text-muted">No ads available at the moment.</p>
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

        new bootstrap.Carousel(document.getElementById('productCarousel'), {
            interval: 5000,
            wrap: true,
            pause: 'hover',
            touch: true
        });
    });
</script>
@endsection
