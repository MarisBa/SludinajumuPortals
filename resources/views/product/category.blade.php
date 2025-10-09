@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-danger border-2 rounded-lg">
                <div class="card-header text-white text-center fw-bold rounded-top" style="background-color: #dc3545;">
                    <i class="bi bi-funnel-fill me-1"></i> Filter By Subcategory
                </div>
                <div class="card-body p-3">
                    @forelse($filterBySubcategory as $subcategory)
                        <p class="mb-2">
                            {{-- Assuming subcategory.show route is correct and handles {categorySlug}/{subcategorySlug} --}}
                            <a href="{{ route('subcategory.show', [
                                'categorySlug' => $subcategory->category->slug,
                                'subcategorySlug' => $subcategory->slug
                            ]) }}" class="text-decoration-none text-dark d-block p-1 rounded hover-bg-light">
                                <i class="bi bi-folder-fill me-2 text-danger"></i> {{ $subcategory->name }}
                            </a>
                        </p>
                    @empty
                        <p class="text-muted fst-italic">No subcategories available.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            
            {{-- NEW: Dynamic Heading to Show Context (Subcategory Name) --}}
            <h2 class="mb-4 fw-bold text-dark border-bottom pb-2">
                @isset($currentSubcategory)
                    Advertisements in: <span class="text-danger">{{ $currentSubcategory->name }}</span>
                @else
                    All Advertisements
                @endisset
            </h2>

            <div class="row">
                @forelse($advertisements as $advertisement)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 hover-shadow rounded-lg">
                            {{-- Image route updated with asset helper for better handling, assuming a local path --}}
                            <img src="{{ route('ad.image', basename($advertisement->feature_image)) }}" class="card-img-top p-2 rounded-lg" alt="Ad Image" style="object-fit: cover; height: 180px;">
                            <div class="card-body p-3 text-center">
                                <p class="card-text mb-1 fw-bold text-truncate">{{ $advertisement->name }}</p>
                                <strong class="text-success fs-5">USD {{ number_format($advertisement->price, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                @empty 
                    <div class="col-12 text-center mt-5">
                        <div class="alert alert-warning border-0 shadow-sm rounded-lg p-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                            Sorry, we couldn't find any products matching this criteria.
                        </div>
                    </div>
                @endforelse
            </div>
            
            {{-- Optional: Pagination --}}
            @if(method_exists($advertisements, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $advertisements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .rounded-lg {
        border-radius: 0.75rem !important;
    }
    .hover-shadow {
        cursor: pointer;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    .hover-bg-light:hover {
        background-color: #f8f9fa; 
    }
</style>
@endsection
