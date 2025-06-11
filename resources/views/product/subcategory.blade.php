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
            <!-- Sorting Tabs -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-2">
                    <div class="d-flex flex-wrap gap-2">
                        <button onclick="sortProducts('latest')" class="btn btn-sm {{ request('sort') == 'latest' || !request('sort') ? 'btn-danger' : 'btn-outline-danger' }}">
                            Latest
                        </button>
                        <button onclick="sortProducts('price-low-to-high')" class="btn btn-sm {{ request('sort') == 'price-low-to-high' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Price: Low to High
                        </button>
                        <button onclick="sortProducts('price-high-to-low')" class="btn btn-sm {{ request('sort') == 'price-high-to-low' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Price: High to Low
                        </button>
                    </div>
                </div>
            </div>

            <div class="row" id="products-container">
                @forelse($advertisements as $advertisement)
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 product-item" 
                         data-price="{{ $advertisement->price }}" 
                         data-date="{{ $advertisement->created_at->timestamp }}">
                        <a href="{{ route('product.view', ['id' => $advertisement->id, 'slug' => $advertisement->slug]) }}" class="text-decoration-none text-dark">
                            <div class="card h-100 border-0 shadow-sm hover-shadow">
                                <img src="{{ route('ad.image', basename($advertisement->feature_image)) }}" class="card-img-top rounded-top img-fluid" style="height: 200px; object-fit: cover;" alt="{{ $advertisement->name }}">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-truncate mb-1">{{ Str::limit($advertisement->name, 40) }}</h6>
                                    <p class="fw-bold text-danger mb-0 price-display">USD {{ number_format($advertisement->price, 2) }}</p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store original order for "latest" sorting
    const productsContainer = document.getElementById('products-container');
    const originalOrder = Array.from(productsContainer.children);
    
    window.sortProducts = function(sortType) {
        const products = Array.from(document.querySelectorAll('.product-item'));
        
        products.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);
            const dateA = parseInt(a.dataset.date);
            const dateB = parseInt(b.dataset.date);
            
            switch(sortType) {
                case 'price-low-to-high':
                    return priceA - priceB;
                case 'price-high-to-low':
                    return priceB - priceA;
                case 'latest':
                default:
                    return dateB - dateA;
            }
        });
        
        // Update URL without reloading (optional)
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortType);
        window.history.pushState({}, '', url);
        
        // Update active button states
        document.querySelectorAll('.card-body button').forEach(btn => {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
        });
        
        event.target.classList.remove('btn-outline-danger');
        event.target.classList.add('btn-danger');
        
        // Re-append sorted products
        productsContainer.innerHTML = '';
        products.forEach(product => {
            productsContainer.appendChild(product);
        });
    };
    
    // Apply initial sort if specified in URL
    const urlParams = new URLSearchParams(window.location.search);
    const sortParam = urlParams.get('sort');
    if (sortParam) {
        sortProducts(sortParam);
    }
});
</script>
@endsection