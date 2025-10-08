<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\Category; // <--- We need the Category model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage; 

class ForumPostController extends Controller
{
    /**
     * Display a listing of the resource (Posts created by the current user).
     * This method shows the user all their own posts.
     */
    public function index()
    {
        // Fetch posts created by the currently logged-in user
        $posts = ForumPost::where('user_id', Auth::id())->latest()->paginate(10);
        
        // Pass the posts to the index view
        return view('forum_posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. Fetch ALL main categories from the database
        $categories = Category::all(); 
        
        // 2. Pass categories to the view, which will use them to build the dropdown
        return view('forum_posts.create', compact('categories')); 
    }

    /**
     * Store a newly created resource in storage (when the user submits the form).
     */
    public function store(Request $request)
    {
        // 1. Validation - Ensures required fields are filled and category_id is valid
        try {
            $validated = $request->validate([
                // Ensures category_id is required and exists in the 'categories' table
                'category_id' => 'required|exists:categories,id', 
                'title' => 'required|string|max:255',
                'body' => 'required|string|min:10',
                'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);
        } catch (ValidationException $e) {
            // If validation fails, redirect back with errors and old input
            return back()->withErrors($e->errors())->withInput();
        }

        // 2. Handle Image Upload (Similar logic to your ad creation)
        $imagePath = null;
        if ($request->hasFile('feature_image')) {
            $file = $request->file('feature_image');
            // Store the image in the 'public' disk under a 'forum_posts/images' folder
            $imagePath = $file->store('forum_posts/images', 'public');
        }

        // 3. Create the Forum Post record in the database
        ForumPost::create([
            'user_id' => Auth::id(), // Automatically assign the current authenticated user's ID
            'category_id' => $validated['category_id'], // Save the selected category
            'title' => $validated['title'],
            'body' => $validated['body'],
            'feature_image' => $imagePath,
            'published' => true, // Defaulting to published
        ]);

        // 4. Redirect the user to the list of their posts with a success message
        return redirect()->route('forum.posts.index')->with('success', 'Forum Post created successfully!');
    }
}
