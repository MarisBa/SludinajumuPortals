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
        $categorySlug, $subcategorySlug
    ) {

        return view('product.subcategory');
}
}