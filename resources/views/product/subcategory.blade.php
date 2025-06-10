@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <!-- Filter by Child Category -->
            <div class="card mb-3">
                <div class="card-header text-white text-center" style="background-color: red;">Filter ::</div>
                <div class="card-body">
                    @forelse($filterByChildCategories as $filterchildcategory)
                        @php
                            $child = $filterchildcategory->childcategory ?? null;
                        @endphp
                        @if($child)
                            <p>
                                <a href="{{ route('childcategory.show', [
                                    'categorySlug' => $child->subcategory->category->slug,
                                    'subcategorySlug' => $child->subcategory->slug,
                                    'childCategorySlug' => $child->slug
                                ]) }}">
                                    {{ $child->name }}
                                </a>
                            </p>
                        @endif
                    @empty
                        <p>No filters available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Price Filter -->
            <form action="{{ url()->current() }}" method="GET">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="minPrice">Minimum price</label>
                            <input type="text" name="minPrice" class="form-control" value="{{ request('minPrice') }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="maxPrice">Maximum price</label>
                            <input type="text" name="maxPrice" class="form-control" value="{{ request('maxPrice') }}">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-danger w-100">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            <div class="row">
                @forelse($advertisements as $advertisement)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <a href="{{route('product.view',[$advertisement->id,$advertisement->slug])}}">
                            <img src="{{ route('ad.image', basename($advertisement->feature_image)) }}" class="card-img-top img-thumbnail" alt="{{ $advertisement->name }}">
                            <div class="card-body p-2 text-center">
                                <p class="mb-1">{{ $advertisement->name }}</p>
                                <strong>USD {{ $advertisement->price }}</strong>
                            </div>
                            </a>
                        </div>
                    </div>
                @empty 
                    <div class="col-12 text-center">
                        Sorry, we are unable to find products based on this category.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
