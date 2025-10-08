@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 pb-2 border-bottom">
        <h1 class="h2 mb-3 mb-sm-0">Your Forum Posts</h1>

        {{-- Create New Post Button --}}
        <a href="{{ route('forum.posts.create') }}" class="btn btn-primary btn-lg d-flex align-items-center">
            <svg class="bi me-2" width="20" height="20" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create New Post
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Posts Grid --}}
    <div class="row g-4">
        @forelse ($posts as $post)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <a href="{{ route('forum.posts.show', $post->id) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm border-top border-primary hover-shadow">
                        {{-- Feature Image --}}
                        @if ($post->feature_image)
                            <img src="{{ asset('storage/' . $post->feature_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 160px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 160px;">
                                <svg class="bi" width="40" height="40" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-9-9h.01M6 19h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <small class="text-muted ms-2">No Feature Image</small>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            {{-- Title --}}
                            <h5 class="card-title">{{ $post->title }}</h5>

                            {{-- Category --}}
                            <p class="text-primary mb-2 small">Category: {{ $post->category->name ?? 'Uncategorized' }}</p>

                            {{-- Body Snippet --}}
                            <p class="card-text text-muted mb-3 flex-grow-1">{{ Str::limit($post->body, 90) }}</p>

                            {{-- Footer/Metadata --}}
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-auto">
                                <small class="text-muted">Created: {{ $post->created_at->diffForHumans() }}</small>
                                <span class="text-primary fw-semibold">View Details
                                    <svg class="bi ms-1" width="16" height="16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center p-5 bg-light rounded shadow-sm">
                <h3 class="mb-3">No Posts Found</h3>
                <p class="text-muted mb-3">You haven't created any posts yet. Click the button above to start a discussion!</p>
                <a href="{{ route('forum.posts.create') }}" class="text-primary fw-medium">Create your first post now.</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
</div>

{{-- Optional Hover Shadow CSS --}}
<style>
.hover-shadow:hover {
    transform: scale(1.02);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,.15) !important;
}
</style>
@endsection
