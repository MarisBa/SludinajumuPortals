@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <!-- Filter by Child Category -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white text-center fw-bold">Filter Categories</div>
                <div class="card-body">
                    @forelse($filterByChildCategories as $filterchildcategory)
                        @php $child = $filterchildcategory->childcategory ?? null; @endphp
                        @if($child)
                            <div class="mb-2">
                                <a href="{{ route('childcategory.show', [
                                    'categorySlug' => $child->subcategory->category->slug,
                                    'subcategorySlug' => $child->subcategory->slug,
                                    'childCategorySlug' => $child->slug
                                ]) }}" class="text-decoration-none text-dark">
                                    <i class="bi bi-tag"></i> {{ $child->name }}
                                </a>
                            </div>
                        @endif
                    @empty
                        <p>No filters available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Price Filter -->
            <form action="{{ url()->current() }}" method="GET">
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold text-center">Price Range</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="minPrice" class="form-label">Min Price</label>
                            <input type="number" name="minPrice" class="form-control" placeholder="e.g. 50" value="{{ request('minPrice') }}">
                        </div>
                        <div class="mb-3">
                            <label for="maxPrice" class="form-label">Max Price</label>
                            <input type="number" name="maxPrice" class="form-control" placeholder="e.g. 500" value="{{ request('maxPrice') }}">
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Apply Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Main content -->
        <div class="col-lg-9">
            <div class="row">
                @forelse($advertisements as $advertisement)
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <a href="{{ route('product.view', ['id' => $advertisement->id, 'slug' => $advertisement->slug]) }}" class="text-decoration-none text-dark">
                            <div class="card h-100 border-0 shadow-sm hover-shadow">
                                <img src="{{ route('ad.image', basename($advertisement->feature_image)) }}" class="card-img-top rounded-top img-fluid" style="height: 200px; object-fit: cover;" alt="{{ $advertisement->name }}">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-truncate mb-1">{{ Str::limit($advertisement->name, 40) }}</h6>
                                    <p class="fw-bold text-danger mb-0">USD {{ number_format($advertisement->price, 2) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty 
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">
                            Sorry, we are unable to find products based on this category.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<style>
    .hover-shadow:hover {
    transform: translateY(-5px);
    transition: 0.3s ease;
}

</style>
@endsection
