<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ForumPostController extends Controller
{
    // Display all posts by the current user
    public function index()
    {
        $posts = ForumPost::where('user_id', Auth::id())->latest()->paginate(10);
        return view('forum_posts.index', compact('posts'));
    }

    // Show the form to create a new post
    public function create()
    {
        $categories = Category::all();
        return view('forum_posts.create', compact('categories'));
    }

    // Store a new forum post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('feature_image') 
            ? $request->file('feature_image')->store('forum_posts/images', 'public') 
            : null;

        ForumPost::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'feature_image' => $imagePath,
            'published' => true,
        ]);

        return redirect()->route('forum.posts.index')->with('success', 'Forum Post created successfully!');
    }

    // Show a single post (for the show.blade page)
    public function show(ForumPost $forum_post)
    {
        return view('forum_posts.show', ['post' => $forum_post]);
    }

    // Show the edit form for a post
    public function edit(ForumPost $forum_post)
    {
        $this->authorize('update', $forum_post); // optional: ensures only owner can edit
        $categories = Category::all();
        return view('forum_posts.edit', compact('forum_post', 'categories'));
    }

    // Update a post
    public function update(Request $request, ForumPost $forum_post)
    {
        $this->authorize('update', $forum_post);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('feature_image')) {
            // Delete old image if exists
            if ($forum_post->feature_image) {
                Storage::disk('public')->delete($forum_post->feature_image);
            }
            $forum_post->feature_image = $request->file('feature_image')->store('forum_posts/images', 'public');
        }

        $forum_post->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        return redirect()->route('forum.posts.show', $forum_post->id)
                         ->with('success', 'Forum Post updated successfully!');
    }

    // Delete a post
    public function destroy(ForumPost $forum_post)
    {
        $this->authorize('delete', $forum_post);

        if ($forum_post->feature_image) {
            Storage::disk('public')->delete($forum_post->feature_image);
        }

        $forum_post->delete();

        return redirect()->route('forum.posts.index')
                         ->with('success', 'Forum Post deleted successfully!');
    }
}
