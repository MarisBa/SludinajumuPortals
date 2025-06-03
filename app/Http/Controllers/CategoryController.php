<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryFormRequest; // Assuming you have a form request for validation
use App\Models\Category;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::get();
        return view('backend.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('backend.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryFormRequest $request)
{
    // Store the uploaded file in 'storage/app/public/category'
    // and return the path relative to 'public' disk (e.g., "category/abc.jpg")
    $imagePath = $request->file('image')->store('category', 'public');
    // Returns: category/filename.png

    Category::create([
        'name' => $request->name,
        'image' => 'storage/' . $imagePath, // saves: storage/category/filename.png
        'slug' => \Str::slug($request->name),
    ]);


    return redirect()->back()->with('success', 'Category created successfully!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        return view('backend.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $category = Category::find($id);
        if ($request->hasFile('image')) {
            Storage::delete($category->image);
            $imagePath = $request->file('image')->store('category', 'public');
            $category->update(['name' => $request->name, 'image' => $image]);
        }
         $category->update(['name' => $request->name]);
         return redirect()->route('category.index')->with('message', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
