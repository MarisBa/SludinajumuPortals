<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ChildcategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AdImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontendController;


Route::get('/', [MenuController::class, 'menu'])->name('home');

Route::get('/home', function () {
    return view('index');
});

Route::get('/auth', function () {
    return view('backend.admin.index');
});

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Authenticated admin routes (for categories)
Route::prefix('auth')->middleware('auth')->group(function () {
    Route::resource('/category', CategoryController::class);
    Route::resource('/subcategory', SubcategoryController::class);
    Route::resource('/childcategory', ChildcategoryController::class);
});

// Ads routes
Route::middleware('auth')->group(function () {
    Route::get('/ads', [AdvertisementController::class, 'index'])->name('ads.index');
    Route::get('/ads/create', [AdvertisementController::class, 'create'])->name('ads.create');
    Route::post('/ads/store', [AdvertisementController::class, 'store'])->name('ads.store');
    Route::get('/ads/{id}/edit', [AdvertisementController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{id}/update', [AdvertisementController::class, 'update'])->name('ads.update');
    Route::delete('/ads/{id}', [AdvertisementController::class, 'destroy'])->name('ads.destroy');
});



// Serve private ad images via controller
Route::get('/ad-image/{filename}', [AdImageController::class, 'show'])->name('ad.image');

// Dynamic dependent dropdowns (AJAX)
Route::get('/get-subcategories/{category_id}', [CategoryController::class, 'getSubcategories']);
Route::get('/get-childcategories/{subcategory_id}', [CategoryController::class, 'getChildcategories']);
Route::get('/get-states/{country_id}', [LocationController::class, 'getStates']);

// Global view composer for menus
View::composer(['*'], function ($view) {
    $menus = \App\Models\Category::with('subcategories')->get();
    $view->with('menus', $menus);
});


Route::resource('ads', AdvertisementController::class);

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile', 'ProfileController@updateProfile')->name('update.profile')->middleware('auth');


// Most specific → Least specific



Route::get('/product/{categorySlug}/{subcategorySlug}/{childCategorySlug}',
    [FrontendController::class, 'findBasedOnChildcategory'])
    ->name('childcategory.show');

Route::get('/product/{categorySlug}/{subcategorySlug}',
    [FrontendController::class, 'findBasedOnSubcategory'])
    ->name('subcategory.show');

Route::get('/product/{categorySlug}',
    [FrontendController::class, 'findBasedOnCategory'])
    ->name('category.show');

// Put this LAST to avoid conflict
Route::get('/product/{id}/{slug}',
    [FrontendController::class, 'show'])
    ->name('product.view');



