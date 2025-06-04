<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ChildcategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdvertisementController;











/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/home', function () {
    return view('index');
});

// Authentication Routes


Route::get('/auth', function () {
    return view('backend.admin.index'); // Make sure this matches the path
});

Route::get('/dashboard', 'DashboardController@index');

Route::group(['prefix' => 'auth'], function () {
    Route::resource('/category', CategoryController::class);
    Route::resource('/subcategory', SubcategoryController::class);
    Route::resource('/childcategory', ChildcategoryController::class);

});

Route::get('/', [MenuController::class, 'menu']);


Route::get('/ads/create', [AdVertisementController::class, 'create']);

View::composer(['*'], function($view){
    $menus = App\Models\Category::with('subcategories')->get();
    $view->with('menus', $menus);
});

Route::resource('ads', AdvertisementController::class);

Route::post('/ads/store', [AdvertisementController::class, 'store'])->middleware('auth')->name('ads.store');
