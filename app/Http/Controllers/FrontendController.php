<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Subcategory;

class FrontendController extends Controller
{
        public function findBasedOnSubcategory(
        $categorySlug, Subcategory $subcategorySlug
    ) {
        $advertisements = $subcategorySlug->ads;

         $filterByChildCategories = $subcategorySlug->ads->unique('childcategory_id');
        return view(
    'product.subcategory',
    compact('advertisements', 'filterByChildCategories')
);

}
}