<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Subcategory;


class FrontendController extends Controller
{
    public function browse(Request $request)
    {
        $query = Advertisement::where('published', 1)->whereNotNull('feature_image');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('product_condition', $request->condition);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'latest');
        $query = match($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $ads = $query->paginate(12)->withQueryString();
        $categories = Category::withCount(['ads' => fn($q) => $q->where('published', 1)])->get();
        $totalResults = $ads->total();

        return view('product.browse', compact('ads', 'categories', 'totalResults'));
    }

        public function findBasedOnCategory(Category $categorySlug)
    {
        $advertisements = $categorySlug->ads;
        $filterBySubcategory = Subcategory::where('category_id',$categorySlug->id)->get();
        return view('product.category',compact('advertisements','filterBySubcategory'));
    }

        public function findBasedOnSubcategory( Request $request,
        $categorySlug, Subcategory $subcategorySlug
    ) {


        $advertisementBasedOnFilter = Advertisement::where(
            'subcategory_id',
            $subcategorySlug->id
        )->when($request->minPrice, function ($query, $minPrice) {
            return $query->where('price', '>=', $minPrice);
        })->when($request->maxPrice, function ($query, $maxPrice) {
            return $query->where('price', '<=', $maxPrice);
        })->get();

        $advertisementWithoutFilter = $subcategorySlug->ads;

         $filterByChildCategories = $subcategorySlug->ads->unique('childcategory_id');

         $advertisements = $request->minPrice || $request->maxPrice ?
         $advertisementBasedOnFilter : $advertisementWithoutFilter;
        return view(
    'product.subcategory',
    compact('advertisements', 'filterByChildCategories')
);

}

 public function findBasedOnChildcategory($categorySlug,
        Subcategory $subcategorySlug,
        Childcategory $childCategorySlug,
        Request $request){

        $advertisementBasedOnFilter = Advertisement::where(
            'childcategory_id',
            $childCategorySlug->id
        )->when($request->minPrice, function ($query, $minPrice) {
            return $query->where('price', '>=', $minPrice);
        })->when($request->maxPrice, function ($query, $maxPrice) {
            return $query->where('price', '<=', $maxPrice);
        })->get();
        $advertisementWithoutFilter = $childCategorySlug->ads;

         $filterByChildCategories = $subcategorySlug->ads->unique('childcategory_id');

         $advertisements = $request->minPrice || $request->maxPrice ?
        $advertisementBasedOnFilter : $advertisementWithoutFilter;
        return view(
    'product.childcategory',
    compact('advertisements', 
    'filterByChildCategories')
);
 }

public function show($id, $slug = null)
{
    $advertisement = Advertisement::with(['user', 'country', 'state', 'city', 'category', 'subcategory'])->findOrFail($id);

    $similarAds = Advertisement::where('published', 1)
        ->where('id', '!=', $advertisement->id)
        ->where('category_id', $advertisement->category_id)
        ->whereNotNull('feature_image')
        ->latest()
        ->take(4)
        ->get();

    $sellerAds = Advertisement::where('published', 1)
        ->where('id', '!=', $advertisement->id)
        ->where('user_id', $advertisement->user_id)
        ->whereNotNull('feature_image')
        ->latest()
        ->take(4)
        ->get();

    return view('product.show', compact('advertisement', 'similarAds', 'sellerAds'));
}

}