@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
{{-- Hero Carousel --}}
<div class="position-relative">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff;"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff;"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff;"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div style="height: 420px; overflow: hidden;">
                    <img src="/slider/slider1.png" class="d-block w-100 h-100" style="object-fit: cover;" alt="First slide">
                </div>
            </div>
            <div class="carousel-item">
                <div style="height: 420px; overflow: hidden;">
                    <img src="/slider/slider2.png" class="d-block w-100 h-100" style="object-fit: cover;" alt="Second slide">
                </div>
            </div>
            <div class="carousel-item">
                <div style="height: 420px; overflow: hidden;">
                    <img src="/slider/slider3.png" class="d-block w-100 h-100" style="object-fit: cover;" alt="Third slide">
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" style="background-color: rgba(0,0,0,0.4); border-radius: 50%; padding: 20px;" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" style="background-color: rgba(0,0,0,0.4); border-radius: 50%; padding: 20px;" aria-hidden="true"></span>
        </button>
    </div>
    {{-- Hero Overlay --}}
    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: linear-gradient(135deg, rgba(30,41,59,0.7) 0%, rgba(37,99,235,0.4) 100%); pointer-events: none;">
        <div class="container text-white" style="pointer-events: auto;">
            <h1 style="font-size: 2.5rem; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Find What You Need</h1>
            <p style="font-size: 1.15rem; opacity: 0.9; text-shadow: 0 1px 5px rgba(0,0,0,0.3);">Browse thousands of listings in your area</p>
        </div>
    </div>
</div>

{{-- Latest Ads Section --}}
<div class="container" style="margin-top: 3rem; margin-bottom: 3rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="font-weight: 700; color: #1e293b; margin-bottom: 0.25rem;">Latest Ads</h2>
            <p style="color: #64748b; margin-bottom: 0; font-size: 0.95rem;">Recently posted listings</p>
        </div>
        <a href="{{ route('ads.index') }}" class="btn" style="background: #2563eb; color: #fff; font-weight: 600; padding: 0.6rem 1.5rem; border-radius: 0.5rem; transition: all 0.2s;">
            View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    @if($ads->count())
        <div class="row g-4">
            @foreach($ads as $ad)
                <div class="col-lg-3 col-md-4 col-6">
                    <a href="{{ route('product.view', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="text-decoration-none">
                        <div class="card border-0 h-100 ad-card" style="border-radius: 1rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                            <div style="height: 200px; overflow: hidden; background: #f1f5f9;">
                                <img src="{{ str_starts_with($ad->feature_image, 'http') ? $ad->feature_image : route('ad.image', basename($ad->feature_image)) }}"
                                     class="w-100 h-100"
                                     style="object-fit: cover; transition: transform 0.3s ease;"
                                     alt="{{ $ad->name }}">
                            </div>
                            <div class="card-body" style="padding: 1rem 1.1rem;">
                                <h6 style="font-weight: 600; color: #1e293b; margin-bottom: 0.4rem; font-size: 0.95rem;">{{ Str::limit($ad->name, 28) }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-weight: 700; color: #2563eb; font-size: 1.1rem;">&euro;{{ number_format($ad->price, 2) }}</span>
                                    <span style="color: #94a3b8; font-size: 0.8rem;">
                                        <i class="bi bi-clock me-1"></i>{{ $ad->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
            <p class="mt-3" style="color: #94a3b8; font-size: 1.1rem;">No ads available yet. Be the first to post!</p>
            <a href="{{ url('/ads/create') }}" class="btn mt-2" style="background: #2563eb; color: #fff; font-weight: 600; padding: 0.6rem 1.5rem; border-radius: 0.5rem;">
                <i class="bi bi-plus-lg me-1"></i> Post an Ad
            </a>
        </div>
    @endif
</div>

<style>
    .ad-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12) !important;
    }
    .ad-card:hover img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Carousel(document.getElementById('mainCarousel'), {
            interval: 4000,
            wrap: true,
            pause: 'hover'
        });
    });
</script>
@endsection
