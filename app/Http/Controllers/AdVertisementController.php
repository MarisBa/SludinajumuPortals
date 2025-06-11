<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Advertisement;
use Illuminate\Support\Str;
use App\Http\Requests\AdsFormRequest; // Assuming you have a form request for validation
use App\Http\Requests\AdsFormUpdateRequest; // ✅ This is the key line
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Advertisement::latest()->where('user_id', auth()->user()->id)->get();
        return view('ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('ads.create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(AdsFormRequest $request)
    {
        $data = $request->all();
        $featureImage = $request->file('feature_image')->store('public/category');
        $firstImage = $request->file('first_image')->store('public/category');
        $secondImage = $request->file('second_image')->store('public/category');
        $data['feature_image'] =  $featureImage;
        $data['first_image'] =  $firstImage;
        $data['second_image'] =  $secondImage;
        $data['slug'] =  Str::slug($request->name);
        $data['user_id'] = auth()->user()->id;

        Advertisement::create($data);
        return redirect()->route('ads.index')->with('message', 'Advertisement created successfully');
    }

    /**
     * Display the specified resource.
     */
        public function show($id)
        {
            $ad = Advertisement::findOrFail($id);
            return view('ads.show', compact('ad'));
        }

        


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ad =  Advertisement::find($id);

        return view('ads.edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdsFormUpdateRequest $request, $id)
    {
        $ad = Advertisement::find($id);
        $featureImage = $ad->feature_image;
        $firstImage = $ad->first_image;
        $secondImage = $ad->second_image;
        $data = $request->all();
        if ($request->hasFile('feature_image')) {
            $featureImage = $request->file('feature_image')->store('public/category');
        }
        if ($request->hasFile('first_image')) {
            $firstImage = $request->file('first_image')->store('public/category');
        }
        if ($request->hasFile('second_image')) {
            $secondImage = $request->file('second_image')->store('public/category');
        }
        $data['feature_image'] = $featureImage;
        $data['first_image'] = $firstImage;
        $data['second_image'] = $secondImage;

        $ad->update($data);
        return redirect()->route('ads.index')->with('message','Your ad was updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
// In AdvertisementController.php
public function destroy($id)
{
    $ad = Advertisement::findOrFail($id);
    
    // Delete the image file if needed
    if (Storage::exists($ad->feature_image)) {
        Storage::delete($ad->feature_image);
    }
    
    $ad->delete();
    
    return redirect()->route('ads.index')
        ->with('success', 'Advertisement deleted successfully');
}


public function homepage()
{
    $ads = Advertisement::where('published', 1)
        ->whereNotNull('feature_image')
        ->latest()
        ->take(6)
        ->get();

    return view('home', compact('ads'));
}

}