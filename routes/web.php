<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ChildcategoryController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AdImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ForumPostController; // <--- NEW IMPORT

/*
|--------------------------------------------------------------------------
| Global View Composer
|--------------------------------------------------------------------------
*/
View::composer(['*'], function ($view) {
    $menus = \App\Models\Category::with('subcategories')->get();
    $view->with('menus', $menus);
});

// Home and product viewing routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/auth', function () {
    return view('backend.admin.index');
});

Route::prefix('product')->group(function () {
    Route::get('/{id}/{slug}', [FrontendController::class, 'show'])->name('product.view');
    Route::get('/{categorySlug}/{subcategorySlug}/{childCategorySlug}', [FrontendController::class, 'findBasedOnChildcategory'])->name('childcategory.show');
    Route::get('/{categorySlug}/{subcategorySlug}', [FrontendController::class, 'findBasedOnSubcategory'])->name('subcategory.show');
    Route::get('/{categorySlug}', [FrontendController::class, 'findBasedOnCategory'])->name('category.show');
});

// Utility and dynamic routes
Route::get('/ad-image/{filename}', [AdImageController::class, 'show'])->name('ad.image');
Route::get('/get-subcategories/{category_id}', [CategoryController::class, 'getSubcategories']);
Route::get('/get-childcategories/{subcategory_id}', [CategoryController::class, 'getChildcategories']);
Route::get('/get-states/{country_id}', [LocationController::class, 'getStates']);


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Backend/Admin/User Management)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('update.profile');

    Route::prefix('auth')->group(function () {
        Route::resource('/category', CategoryController::class);
        Route::resource('/subcategory', SubcategoryController::class);
        Route::resource('/childcategory', ChildcategoryController::class);
    });

    // Existing Ads routes
    Route::resource('ads', AdvertisementController::class);

    // FORUM POSTS: This single line creates all 7 routes (index, create, store, edit, etc.)
    Route::resource('forum-posts', ForumPostController::class)->names('forum.posts'); 
});
