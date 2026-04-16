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
        $ads = Advertisement::with(['category', 'subcategory'])->latest()->where('user_id', auth()->user()->id)->get();
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
        $data['slug'] = Str::slug($request->name);
        $data['user_id'] = auth()->user()->id;

        // Handle multiple images (up to 12)
        $allImages = [];

        if ($request->hasFile('ad_images')) {
            foreach ($request->file('ad_images') as $file) {
                $allImages[] = $file->store('public/category');
            }
        }

        // Legacy single file inputs (fallback)
        if (empty($allImages)) {
            if ($request->hasFile('feature_image')) $allImages[] = $request->file('feature_image')->store('public/category');
            if ($request->hasFile('first_image')) $allImages[] = $request->file('first_image')->store('public/category');
            if ($request->hasFile('second_image')) $allImages[] = $request->file('second_image')->store('public/category');
        }

        // Set feature_image to the first one for backward compatibility
        $data['feature_image'] = $allImages[0] ?? null;
        $data['first_image'] = $allImages[1] ?? null;
        $data['second_image'] = $allImages[2] ?? null;
        $data['images'] = count($allImages) > 3 ? json_encode(array_slice($allImages, 3)) : null;

        // Remove ad_images from $data since it's not a column
        unset($data['ad_images']);

        Advertisement::create($data);
        return redirect()->route('ads.index')->with('message', 'Sludinājums veiksmīgi izveidots!');
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
        $ad = Advertisement::with(['category', 'subcategory', 'childcategory', 'country', 'state', 'city'])->findOrFail($id);
        $categories = Category::all();

        return view('ads.edit', compact('ad', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdsFormUpdateRequest $request, $id)
    {
        $ad = Advertisement::findOrFail($id);
        $data = $request->except(['ad_images', 'feature_image', 'first_image', 'second_image', '_token', '_method']);

        // Handle new images
        if ($request->hasFile('ad_images')) {
            $allImages = [];
            foreach ($request->file('ad_images') as $file) {
                $allImages[] = $file->store('public/category');
            }
            $data['feature_image'] = $allImages[0] ?? $ad->feature_image;
            $data['first_image'] = $allImages[1] ?? $ad->first_image;
            $data['second_image'] = $allImages[2] ?? $ad->second_image;
            if (count($allImages) > 3) {
                $data['images'] = json_encode(array_slice($allImages, 3));
            }
        }

        $ad->update($data);
        return redirect()->route('ads.index')->with('message', 'Sludinājums veiksmīgi atjaunināts!');

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