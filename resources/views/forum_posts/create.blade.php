@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4">
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">
<h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Create New Forum Post</h1>
<p class="text-gray-600 mb-6">Select a category and write your post content. Images are optional.</p>

    {{-- Form action targets the store method of the forum.posts resource route --}}
    <form method="POST" action="{{ route('forum.posts.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Messages for Success/Errors -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                <p class="font-bold">Please correct the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>&bull; {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Category Selection (REQUIRED) -->
        <div class="mb-5">
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Select Category</label>
            <select name="category_id" id="category_id" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Choose a Category --</option>
                {{-- Loop through categories passed from the ForumPostController --}}
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Title Field -->
        <div class="mb-5">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Post Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" 
                required maxlength="255">
            @error('title')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Body Field (Textarea) -->
        <div class="mb-5">
            <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Post Content</label>
            <textarea name="body" id="body" rows="8" 
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" 
                required>{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Feature Image (Optional) -->
        <div class="mb-6">
            <label for="feature_image" class="block text-sm font-medium text-gray-700 mb-1">Feature Image (Optional)</label>
            <input type="file" name="feature_image" id="feature_image" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">
            @error('feature_image')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submission Button -->
        <div class="pt-4 border-t border-gray-200">
            <button type="submit" 
                class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Publish Forum Post
            </button>
        </div>
    </form>
</div>

</div>
@endsection