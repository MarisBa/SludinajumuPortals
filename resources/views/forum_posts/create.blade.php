@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Create New Forum Post</h1>
        <p class="lead text-secondary">
            Share your knowledge with the community. Select a category and start writing!
        </p>
    </div>

    <!-- Card Container -->
    <div class="card shadow-lg rounded-4 p-4 p-md-5">
        
        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('forum.posts.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Category Selection -->
            <div class="mb-4">
                <label for="category_id" class="form-label fw-semibold">Select Category</label>
                <select class="form-select" name="category_id" id="category_id" required>
                    <option value="">-- Choose a Topic Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="form-label fw-semibold">Post Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="form-control" placeholder="e.g., Best practices for Laravel 11 routing" required maxlength="255">
                @error('title')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Body -->
            <div class="mb-4">
                <label for="body" class="form-label fw-semibold">Post Content</label>
                <textarea name="body" id="body" rows="8" class="form-control" placeholder="Write the detailed body of your post here..." required>{{ old('body') }}</textarea>
                @error('body')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Feature Image -->
            <div class="mb-4">
                <label for="feature_image" class="form-label fw-semibold">Feature Image (Optional)</label>
                <input type="file" name="feature_image" id="feature_image" class="form-control" accept="image/*">
                @error('feature_image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                    Publish Forum Post
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
