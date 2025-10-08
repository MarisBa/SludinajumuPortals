@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Back Button --}}
    <a href="{{ route('forum.posts.index') }}" class="btn btn-outline-secondary mb-4">
        &larr; Back to My Posts
    </a>

    {{-- Post Card --}}
    <div class="card shadow-sm">
        {{-- Feature Image --}}
        @if ($post->feature_image)
            <img src="{{ asset('storage/' . $post->feature_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 300px; object-fit: cover;">
        @else
            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 300px;">
                <svg class="bi" width="50" height="50" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-9-9h.01M6 19h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="ms-2 text-muted">No Feature Image</span>
            </div>
        @endif

        <div class="card-body">
            {{-- Title --}}
            <h1 class="card-title">{{ $post->title }}</h1>

            {{-- Category --}}
            <p class="text-primary mb-2 small">Category: {{ $post->category->name ?? 'Uncategorized' }}</p>

            {{-- Author & Date --}}
            <p class="text-muted small mb-3">
                Posted by: {{ $post->user->name ?? 'Unknown' }} | {{ $post->created_at->format('F j, Y, g:i a') }}
            </p>

            {{-- Body --}}
            <div class="mb-4">
                {!! nl2br(e($post->body)) !!}
            </div>

            {{-- Optional Footer Actions --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('forum.posts.edit', $post->id) }}" class="btn btn-warning">Edit Post</a>

                <form action="{{ route('forum.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
