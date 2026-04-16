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
use App\Models\Advertisement;



Route::get('/home', function () {
    $ads = \App\Models\Advertisement::where('published', 1)
        ->whereNotNull('feature_image')
        ->latest()
        ->take(8)
        ->get();

    $totalAds = \App\Models\Advertisement::where('published', 1)->count();
    $totalCategories = \App\Models\Category::count();
    $totalUsers = \App\Models\User::count();

    return view('index', compact('ads', 'totalAds', 'totalCategories', 'totalUsers'));
});



Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // or any other route like '/' or '/auth'
})->name('logout');


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
Route::get('/get-cities/{state_id}', [LocationController::class, 'getCities']);

// Global view composer for menus
View::composer(['*'], function ($view) {
    $menus = \App\Models\Category::with('subcategories')->get();
    $view->with('menus', $menus);
});


Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');

// Account settings routes
Route::prefix('account')->middleware('auth')->group(function () {
    // Profile
    Route::post('/profile', [\App\Http\Controllers\Account\ProfileController::class, 'update'])->name('account.profile.update');

    // Username check (JSON)
    Route::get('/username/check', [\App\Http\Controllers\Account\UsernameController::class, 'check'])->name('account.username.check');

    // Password
    Route::post('/password', [\App\Http\Controllers\Account\PasswordController::class, 'update'])
        ->name('account.password.update')
        ->middleware('throttle:5,15');

    // Phone verification
    Route::post('/phone/send-otp', [\App\Http\Controllers\Account\PhoneVerificationController::class, 'send'])
        ->name('account.phone.send')
        ->middleware('throttle:3,10');
    Route::post('/phone/verify-otp', [\App\Http\Controllers\Account\PhoneVerificationController::class, 'verify'])
        ->name('account.phone.verify')
        ->middleware('throttle:5,10');
    Route::delete('/phone', [\App\Http\Controllers\Account\PhoneVerificationController::class, 'destroy'])
        ->name('account.phone.destroy');

    // Notifications
    Route::post('/notifications', [\App\Http\Controllers\Account\NotificationPreferencesController::class, 'update'])
        ->name('account.notifications.update');

    // Privacy
    Route::post('/privacy', [\App\Http\Controllers\Account\PrivacyController::class, 'update'])
        ->name('account.privacy.update');

    // Data export
    Route::post('/data-export', [\App\Http\Controllers\Account\DataExportController::class, 'request'])
        ->name('account.data-export')
        ->middleware('throttle:2,60');
});

// Legacy profile update route (redirect to new)
Route::post('/profile', [\App\Http\Controllers\Account\ProfileController::class, 'update'])->name('update.profile')->middleware('auth');


// Most specific → Least specific



// Public browse page
Route::get('/browse', [FrontendController::class, 'browse'])->name('browse');

// Specific to general — avoid conflicts
Route::get('/product/{categorySlug}/{subcategorySlug}/{childCategorySlug}', [FrontendController::class, 'findBasedOnChildcategory'])->name('childcategory.show');

Route::get('/product/{categorySlug}/{subcategorySlug}', [FrontendController::class, 'findBasedOnSubcategory'])->name('subcategory.show');

Route::get('/product/{categorySlug}', [FrontendController::class, 'findBasedOnCategory'])->name('category.show');

Route::get('/product-detail/{id}/{slug}', [FrontendController::class, 'show'])->name('product.view');

