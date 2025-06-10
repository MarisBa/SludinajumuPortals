@extends('layouts.app')

@section('content')
<!-- Main Slider Carousel -->
<div class="container-fluid px-0">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>
        
        <!-- Slides -->
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
        
        <!-- Controls -->
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

<!-- Product Carousel -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Car</h1>
        <a href="#" class="btn btn-link">View all</a>
    </div>
    
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <!-- First Slide with cars 1-4 -->
            <div class="carousel-item active">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car1.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 1">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0" style="color: blue;">Toyota trx</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car2.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 2">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">Toyota Yaris</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car3.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 3">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">BMW m4</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car4.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 4">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">BMW e39</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Second Slide with cars 5-8 -->
            <div class="carousel-item">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car5.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 5">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">Audi a5</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car6.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 6">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">Lamborgini evolution</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car7.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 7">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">Aston Martin</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                <img src="/product/car8.jpg" class="card-img-top img-fluid h-100 w-100 object-fit-cover" alt="Car 8">
                            </div>
                            <div class="card-footer text-center">
                                <p class="mb-0">BMW m3</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize main carousel
        var mainCarousel = new bootstrap.Carousel(document.getElementById('mainCarousel'), {
            interval: 3000,
            wrap: true,
            pause: 'hover'
        });
        
        // Initialize product carousel with different settings
        var productCarousel = new bootstrap.Carousel(document.getElementById('productCarousel'), {
            interval: 5000, // Slower transition
            wrap: true,
            pause: 'hover',
            touch: true // Enable touch swiping
        });
    });
</script>
@endsection