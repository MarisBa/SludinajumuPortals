<?php

namespace App\Http\Controllers;
use App\Http\Requests\SubCategoryFormRequest;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use Illuminate\Support\Str;
class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = Subcategory::with('category')->orderBy('category_id')->get();
        return view('backend.subcategory.index', compact('subcategories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.subcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubCategoryFormRequest $request)
    {
            Subcategory::create([
            'name' => $name = $request->name,
            'slug' => Str::slug($name),
            'category_id' => $request->category_id
        ]);
        return back()->with('message', 'Subcategory created successfully');
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
