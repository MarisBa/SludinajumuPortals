<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Childcategory;
use Illuminate\Support\Str;
use App\Http\Requests\ChildcategoryRequest; 


class ChildcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $childcategories = Childcategory::orderBy('subcategory_id')->get();
       return view('backend.childcategory.index',compact('childcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.childcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChildcategoryRequest $request)
    {
        Childcategory::create([
            'name'=>$name=$request->name,
            'subcategory_id'=>$request->subcategory_id,
            'slug'=>Str::slug($name),
        ]);
        return back()
            ->with('message','Subcategory updated');
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
    public function edit($id)
    {
        $childcategory = Childcategory::find($id);
        return view('backend.childcategory.edit', compact('childcategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChildcategoryRequest $request, string $id)
    {
        $childcategory = Childcategory::findOrFail($id);

        $childcategory->update([
            'name' => $name = $request->name,
            'subcategory_id' => $request->subcategory_id,
            'slug' => Str::slug($name),
        ]);

        return redirect()->route('childcategory.index')->with('message', 'Childcategory updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $childcategory = Childcategory::find($id);

    // Optional: If you have an image or file field to delete
    // if ($childcategory && Storage::exists($childcategory->image)) {
    //     Storage::delete($childcategory->image);
    // }

    if ($childcategory) {
        $childcategory->delete();
    }

    return back()->with('message', 'Childcategory deleted successfully');
}
}
