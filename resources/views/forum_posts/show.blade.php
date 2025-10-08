@extends('layouts.app')

@section('content')

<!-- Global Container: Clean, readable background -->
<div class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('forum.posts.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to All Posts
            </a>
        </div>

        <!-- Main Post Card -->
        <div class="bg-white p-6 sm:p-10 shadow-2xl rounded-2xl border-t-8 border-blue-600">
            
            <!-- Category Tag & Author Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">
                <span class="inline-block px-4 py-1 text-sm font-bold rounded-full bg-blue-100 text-blue-800 mb-3 sm:mb-0">
                    {{ $post->category->name ?? 'General' }}
                </span>
                <div class="text-sm text-gray-500">
                    Posted by <span class="font-medium text-gray-700">{{ $post->user->name ?? 'Anonymous' }}</span> 
                    on {{ $post->created_at->format('M d, Y \a\t H:i') }}
                </div>
            </div>

            <!-- Post Title -->
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight mb-8">
                {{ $post->title }}
            </h1>

            <!-- Feature Image -->
            @if ($post->feature_image)
                <img src="{{ asset('storage/' . $post->feature_image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full max-h-96 object-cover rounded-xl mb-8 shadow-md">
            @endif

            <!-- Post Body Content -->
            <div class="prose max-w-none text-gray-700 leading-relaxed text-lg pb-10 border-b border-gray-200">
                {{-- In a real application, you would ensure this is safely rendered HTML/Markdown --}}
                <p>{{ $post->body }}</p>
                
                {{-- Add more complex formatting here if needed, e.g., code blocks, lists, etc. --}}
            </div>

            <!-- Edit/Delete Actions (Optional - only show if the user owns the post) -->
            @auth
                @if (auth()->id() == $post->user_id)
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 transition duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Edit Post
                        </a>
                        {{-- Include a Delete button with confirmation logic here --}}
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 transition duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Delete
                        </button>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Comments Section Placeholder -->
        <div class="mt-12 bg-white p-6 sm:p-8 rounded-2xl shadow-xl">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-3">Discussion (0 Comments)</h2>
            <p class="text-gray-500">The comments section will go here in a future update!</p>
            {{-- This is where your comments loop and input form would be added --}}
        </div>
    </div>
</div>

@endsection
