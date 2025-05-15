@extends('layouts.app')

@section('content')
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
                <!-- Optional caption -->
                <div class="carousel-caption d-none d-md-block">
                    
                </div>
            </div>
            <div class="carousel-item">
                <img src="/slider/slider2.png" class="d-block w-100" alt="Second slide">
                <!-- Optional caption -->
                <div class="carousel-caption d-none d-md-block">
                    
                </div>
            </div>
            <div class="carousel-item">
                <img src="/slider/slider3.png" class="d-block w-100" alt="Third slide">
                <!-- Optional caption -->
                <div class="carousel-caption d-none d-md-block">
                    
                </div>
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
<script>
    // Optional: Custom carousel settings if needed
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = document.getElementById('mainCarousel');
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 3000, // 3 seconds
            wrap: true,
            pause: 'hover' // Pause on hover
        });
    });
</script>
@endsection