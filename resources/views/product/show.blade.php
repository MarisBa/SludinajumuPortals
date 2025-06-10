@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Left column: Image Carousel & Description -->
        <div class="col-md-6">

            @php
                $images = [];
                if (!empty($advertisement->feature_image)) $images[] = $advertisement->feature_image;
                if (!empty($advertisement->first_image)) $images[] = $advertisement->first_image;
                if (!empty($advertisement->second_image)) $images[] = $advertisement->second_image;
            @endphp

            @if(count($images) > 0)
                <div id="productCarousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @foreach($images as $index => $image)
                            <li data-target="#productCarousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>

                    <div class="carousel-inner">
                        @foreach($images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ route('ad.image', basename($image)) }}" class="d-block w-100" alt="Product Image {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>

                    @if(count($images) > 1)
                        <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    @endif
                </div>
            @else
                <div class="text-center">
                    <img src="{{ asset('images/no-image-available.png') }}" class="img-fluid" alt="No Image Available">
                </div>
            @endif

            <hr>

            <!-- Description -->
            <div class="card mb-3">
                <div class="card-body">
                    <p>{{ $advertisement->description }}</p>
                </div>
            </div>

            <!-- More Info -->
            <div class="card">
                <div class="card-header">More Info</div>
                <div class="card-body">
                    <p><strong>Country:</strong> {{ $advertisement->country->name ?? 'N/A' }}</p>
                    <p><strong>State:</strong> {{ $advertisement->state->name ?? 'N/A' }}</p>
                    <p><strong>Product Link:</strong> <a href="{{ $advertisement->link }}" target="_blank">{{ $advertisement->link }}</a></p>
                    <p><strong>Condition:</strong> {{ $advertisement->product_condition }}</p>
                </div>
            </div>
        </div>

        <!-- Right column: Details & Seller -->
        <div class="col-md-6">
            <h1>{{ $advertisement->name }}</h1>
            <p><strong>Price:</strong> ${{ $advertisement->price }} USD ({{ $advertisement->price_status }})</p>
            <p><strong>Posted:</strong> {{ $advertisement->created_at->diffForHumans() }}</p>
            <p><strong>Location:</strong> {{ $advertisement->listing_location }}</p>

            <hr>

            <!-- Seller Info -->
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/man.jpg') }}" width="80" class="rounded-circle mr-3" alt="Seller Avatar">
                <div>
                    <p class="mb-0"><strong>{{ $advertisement->user->name }}</strong></p>
                    <small>Seller</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
