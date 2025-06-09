<?php

dd('routes/api.php loaded');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiCategoryController;

Route::get('/ping', function () {
    return response()->json(['status' => 'API is working']);
});

Route::get('/category', [ApiCategoryController::class, 'getCategory']);
Route::get('/subcategory', [ApiCategoryController::class, 'getSubcategory']);
Route::get('/childcategory', [ApiCategoryController::class, 'getChildcategory']);