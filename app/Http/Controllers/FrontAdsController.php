<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Advertisement;

class FrontAdsController extends Controller
{
    public function index()
    {
        // Get the "car" category
        $category = Category::categoryCar();
        if (!$category) {
            abort(404, 'Category "car" not found.');
        }

        $firstAds = Advertisement::firstFourAdsInCaurosel($category->id);
        $secondsAds = Advertisement::secondFourAdsInCaurosel($category->id);

        // Get the "electronic" category
        $categoryElectronic = Category::categoryElectronic();
        if (!$categoryElectronic) {
            abort(404, 'Category "electronic" not found.');
        }

        $firstAdsForElectronics = Advertisement::firstFourAdsInCauroselForElectronic($categoryElectronic->id);
        $secondsAdsForElectronics = Advertisement::secondFourAdsInCauroselForElectronic($categoryElectronic->id);

        // Get all categories and latest ads
        $categories = Category::all();
        $advertisements = Advertisement::latest()->paginate(30);

        return view('index', compact(
            'firstAds',
            'secondsAds',
            'category',
            'categoryElectronic',
            'firstAdsForElectronics',
            'secondsAdsForElectronics',
            'advertisements',
            'categories'
        ));
    }
}
