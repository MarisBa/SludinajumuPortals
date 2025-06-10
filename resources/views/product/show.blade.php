@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <!-- Left column: Carousel -->
        <div class="col-md-6">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ route('ad.image', basename($advertisement->feature_image)) }}" class="d-block w-100" alt="Feature Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ route('ad.image', basename($advertisement->first_image)) }}" class="d-block w-100" alt="First Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ route('ad.image', basename($advertisement->second_image)) }}" class="d-block w-100" alt="Second Image">
                    </div>
                </div>

                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

            <hr>

            

            <div class="card">
                    <div class="card-body">
                        <p>{{ $advertisement->description }}</p>
                    </div>
                </div>
                
</div>
            <div class="col-md-6">

            <h1>{{ $advertisement->name }}</h1>
            <p>Price: ${{ $advertisement->price }} USD, {{ $advertisement->price_status }}</p>
            <p>Posted: {{ $advertisement->created_at->diffForHumans() }}</p>
            <p>Listing location: {{ $advertisement->listing_location }}</p>

            <hr>
            <img src="/img/man.jpg"width="120">
            <p>{{ $advertisement->user->name }}</p>
            
    </div>
</div>
@endsection
