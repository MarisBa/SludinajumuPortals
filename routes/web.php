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
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated admin routes (for categories)
Route::prefix('auth')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('/category', CategoryController::class);
    Route::resource('/subcategory', SubcategoryController::class);
    Route::resource('/childcategory', ChildcategoryController::class);
});

// PDF exports
Route::middleware('auth')->group(function () {
    Route::get('/pdf/advertisement/{id}', [\App\Http\Controllers\PdfController::class, 'advertisement'])->name('pdf.ad');
});

// ═══════ MESSAGING ROUTES ═══════
Route::middleware('auth')->prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])
        ->name('index');

    Route::get('/unread-count', [\App\Http\Controllers\MessageController::class, 'unreadCount'])
        ->name('unread-count');

    Route::post('/start', [\App\Http\Controllers\MessageController::class, 'start'])
        ->name('start')
        ->middleware('throttle:10,60');

    Route::get('/{conversation}', [\App\Http\Controllers\MessageController::class, 'show'])
        ->name('show')
        ->whereNumber('conversation');

    Route::post('/{conversation}', [\App\Http\Controllers\MessageController::class, 'store'])
        ->name('store')
        ->whereNumber('conversation')
        ->middleware('throttle:30,1');

    Route::get('/{conversation}/poll', [\App\Http\Controllers\MessageController::class, 'poll'])
        ->name('poll')
        ->whereNumber('conversation');
});

// Admin reports (PDF)
Route::middleware(['auth', 'admin'])->prefix('admin/reports')->name('admin.reports.')->group(function () {
    Route::get('/ads-by-category', [\App\Http\Controllers\PdfController::class, 'adsByCategory'])->name('ads');
    Route::get('/users', [\App\Http\Controllers\PdfController::class, 'usersList'])->name('users');
});

// Admin panel
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/block', [\App\Http\Controllers\Admin\UserManagementController::class, 'block'])->name('users.block');
    Route::post('/users/{user}/unblock', [\App\Http\Controllers\Admin\UserManagementController::class, 'unblock'])->name('users.unblock');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/ads', [\App\Http\Controllers\Admin\AdManagementController::class, 'index'])->name('ads.index');
    Route::delete('/ads/{ad}', [\App\Http\Controllers\Admin\AdManagementController::class, 'destroy'])->name('ads.destroy');
    Route::post('/ads/{ad}/toggle', [\App\Http\Controllers\Admin\AdManagementController::class, 'togglePublished'])->name('ads.toggle');
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

    // Privacy
    Route::post('/privacy', [\App\Http\Controllers\Account\PrivacyController::class, 'update'])
        ->name('account.privacy.update');

    // Data export
    Route::post('/data-export', [\App\Http\Controllers\Account\DataExportController::class, 'request'])
        ->name('account.data-export')
        ->middleware('throttle:2,60');

    // Account deletion
    Route::delete('/delete', [\App\Http\Controllers\Account\ProfileController::class, 'destroy'])
        ->name('account.delete');
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

