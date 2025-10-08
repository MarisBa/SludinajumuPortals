@extends('layouts.app')@section('content')<div class="container mx-auto p-4"><div class="flex justify-between items-center mb-6"><h1 class="text-3xl font-bold text-gray-800">Your Forum Posts</h1>{{-- Link to the creation form --}}<a href="{{ route('forum.posts.create') }}"class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">+ Create New Post</a></div>{{-- Success message display --}}
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    {{-- Loop through the posts passed from the controller's index() method --}}
    @forelse ($posts as $post)
    <div class="bg-white p-6 shadow-xl rounded-lg border-t-4 border-blue-500 hover:shadow-2xl transition duration-300">
        
        @if ($post->feature_image)
            {{-- Displays the feature image if one exists --}}
            <img src="{{ asset('storage/' . $post->feature_image) }}" 
                 alt="{{ $post->title }}" class="w-full h-32 object-cover rounded-md mb-4 border border-gray-200">
        @else
            <div class="w-full h-32 flex items-center justify-center bg-gray-100 rounded-md mb-4 text-gray-500 text-sm">
                No Feature Image
            </div>
        @endif

        <h2 class="text-xl font-semibold text-gray-900 mb-1 line-clamp-2">{{ $post->title }}</h2>
        
        {{-- Displays the category name using the Eloquent relationship --}}
        <p class="text-xs text-blue-600 font-medium mb-3">Category: {{ $post->category->name ?? 'Uncategorized' }}</p>
        
        <p class="text-gray-600 text-sm line-clamp-3">{{ Str::limit($post->body, 100) }}</p>
        
        <div class="mt-4 flex justify-between items-center border-t pt-3">
            <p class="text-xs text-gray-500">Created: {{ $post->created_at->diffForHumans() }}</p>
            <a href="#" class="text-sm text-blue-500 hover:text-blue-700 font-medium">View/Edit</a>
        </div>
    </div>
    @empty
    {{-- Message if the user has no posts --}}
    <p class="text-lg text-gray-500 md:col-span-full mt-4">You haven't created any posts yet. Time to start a discussion!</p>
    @endforelse
</div>

{{-- Pagination links --}}
<div class="mt-8">
    {{ $posts->links() }}
</div>
</div>@endsection