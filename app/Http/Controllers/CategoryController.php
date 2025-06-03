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

    $name = $request->name;

    // Save category to DB with image path set for public access
    Category::create([
        'name' => $name,
        'image' => 'storage/' . $imagePath, // <-- THIS is where it goes
        'slug' => \Str::slug($name),
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
