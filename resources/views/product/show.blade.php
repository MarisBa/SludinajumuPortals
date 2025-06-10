@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Left column -->
        <div class="col-md-6 mb-4">
            @php
                $images = [];
                if (!empty($advertisement->feature_image)) $images[] = $advertisement->feature_image;
                if (!empty($advertisement->first_image)) $images[] = $advertisement->first_image;
                if (!empty($advertisement->second_image)) $images[] = $advertisement->second_image;
            @endphp

            <!-- Image Carousel -->
            @if(count($images) > 0)
                <div id="productCarousel" class="carousel slide mb-4" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @foreach($images as $index => $image)
                            <li data-target="#productCarousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>

                    <div class="carousel-inner rounded shadow">
                        @foreach($images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ route('ad.image', basename($image)) }}" class="d-block w-100 img-fluid" style="max-height: 400px; object-fit: cover;" alt="Product Image {{ $index + 1 }}">
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
                <div class="text-center mb-4">
                    <img src="{{ asset('images/no-image-available.png') }}" class="img-fluid rounded shadow" alt="No Image Available" style="max-height: 400px;">
                </div>
            @endif

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <strong>Description</strong>
                </div>
                <div class="card-body">
                    <p>{{ $advertisement->description }}</p>
                </div>
            </div>

            <!-- More Info -->
            <div class="card">
                <div class="card-header bg-light">
                    <strong>More Info</strong>
                </div>
                <div class="card-body">
                    <p><strong>Country:</strong> {{ $advertisement->country->name ?? 'N/A' }}</p>
                    <p><strong>State:</strong> {{ $advertisement->state->name ?? 'N/A' }}</p>
                    <p><strong>Product Link:</strong> <a href="{{ $advertisement->link }}" target="_blank">{{ $advertisement->link }}</a></p>
                    <p><strong>Condition:</strong> {{ $advertisement->product_condition }}</p>
                </div>
            </div>
        </div>

        <!-- Right column -->
        <div class="col-md-6">
            <div class="mb-4">
                <h2 class="mb-3">{{ $advertisement->name }}</h2>
                <p><strong>Price:</strong> ${{ number_format($advertisement->price, 2) }} USD ({{ $advertisement->price_status }})</p>
                <p><strong>Posted:</strong> {{ $advertisement->created_at->diffForHumans() }}</p>
                <p><strong>Location:</strong> {{ $advertisement->listing_location }}</p>
            </div>

            <hr>

            <!-- Seller Info -->
            <div class="card p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/man.jpg') }}" width="80" height="80" class="rounded-circle mr-3 border" alt="Seller Avatar">
                    <div>
                        <h5 class="mb-0">{{ $advertisement->user->name }}</h5>
                        <small class="text-muted">Seller</small>
                    </div>
                </div>
                <hr>
                <div class="mt-2">
                    <p class="mb-1"><strong>Email:</strong> {{ $advertisement->user->email }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $advertisement->phone_number }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
