@extends('layouts.app')

@section('content')

<!-- Global Container for the Form: Added smooth gradient background -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section with Animation: Enhanced text color -->
        <div class="text-center mb-12">
            <h1 class="text-6xl font-extrabold text-blue-800 leading-tight tracking-tighter animate-fade-in-down">
                Create New Forum Post
            </h1>
            <p class="mt-3 text-xl text-gray-700 font-light">
                Share your knowledge with the community. Select a category and start writing!
            </p>
        </div>

        <!-- Main Card Container: Enhanced shadows and lift effect on hover -->
        <div class="bg-white p-6 sm:p-10 shadow-3xl rounded-3xl ring-1 ring-gray-100 
                    transform transition-all duration-500 hover:shadow-4xl hover:-translate-y-1">
            
            {{-- Messages for Success/Errors --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-8 rounded-lg transition duration-500 shadow-lg animate-fade-in" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold text-lg">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 mb-8 rounded-lg transition duration-500 shadow-lg animate-fade-in" role="alert">
                    <div class="font-bold mb-3 flex items-center text-lg">
                        <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Please correct the following errors
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- Form action targets the store method of the forum.posts resource route --}}
            <form method="POST" action="{{ route('forum.posts.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Category Selection (REQUIRED) -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Category</label>
                    <select name="category_id" id="category_id" required
                        class="form-input-style">
                        <option value="">-- Choose a Topic Category --</option>
                        {{-- Loop through categories passed from the ForumPostController --}}
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title Field -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Post Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                        class="form-input-style" 
                        placeholder="e.g., Best practices for Laravel 11 routing"
                        required maxlength="255">
                    @error('title')
                        <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body Field (Textarea) -->
                <div>
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">Post Content</label>
                    <textarea name="body" id="body" rows="10" 
                        class="form-input-style" 
                        placeholder="Write the detailed body of your post here..."
                        required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Feature Image (Optional): Bolder dashed border -->
                <div class="border-2 border-dashed border-gray-300 p-6 rounded-xl transition duration-300 hover:border-blue-500 hover:bg-blue-50">
                    <label for="feature_image" class="block text-sm font-semibold text-gray-700 mb-2">Feature Image (Optional)</label>
                    
                    <input type="file" name="feature_image" id="feature_image" accept="image/*"
                        class="mt-1 block w-full text-base text-gray-700 cursor-pointer
                            file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0
                            file:text-base file:font-semibold
                            file:bg-blue-600 file:text-white
                            hover:file:bg-blue-700 transition duration-300 ease-in-out"
                        style="padding: 0 !important; border: none !important; background: none !important;">
                    @error('feature_image')
                        <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submission Button: Fixed layout issue with full Tailwind classes -->
                <div class="pt-8">
                    <button type="submit" 
                        class="w-full inline-flex items-center justify-center py-4 px-6 border border-transparent shadow-2xl text-xl font-extrabold rounded-2xl text-white 
                            bg-blue-600 hover:bg-blue-700 transition duration-300 ease-in-out 
                            transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-400 focus:ring-offset-4 focus:ring-offset-gray-50">
                        <svg class="w-6 h-6 mr-3 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 12h14M5 16h14"></path></svg>
                        Publish Forum Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Custom CSS for subtle animations and to fix form field display --}}
<style>
/* Animation for the Header */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translate3d(0, -20px, 0); 
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}
.animate-fade-in-down {
    animation: fadeInDown 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); 
}

/* Custom class to combine input styles and forcefully override potential conflicts */
.form-input-style {
    /* Critical Overrides */
    display: block !important;
    width: 100% !important;
    padding: 1rem !important;
    min-height: 3rem !important; 
    border: 1px solid #d1d5db !important; /* border-gray-300 */
    background-color: white !important;
    color: #1f2937 !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;

    /* Base Styles */
    margin-top: 0.25rem;
    border-radius: 0.75rem; /* rounded-xl */
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) inset; /* subtle inset shadow */
    font-size: 1rem;
    transition-property: all;
    transition-duration: 300ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);

    /* Focus Styles */
    outline: none !important;
    border-color: #3b82f6 !important; /* Blue-500 on focus */
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5) !important; /* Custom blue ring shadow */
}

/* Specific style for Textarea */
textarea.form-input-style {
    min-height: 10rem !important;
}

/* Custom shadow utilities for the card */
.shadow-3xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
.hover\:shadow-4xl:hover {
    box-shadow: 0 30px 40px -10px rgba(0, 0, 0, 0.15), 0 15px 15px -10px rgba(0, 0, 0, 0.05);
}
</style>

@endsection
