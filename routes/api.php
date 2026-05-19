<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\PingController;

Route::get('/ping', PingController::class);

Route::get('/category', [ApiCategoryController::class, 'getCategory']);
Route::get('/subcategory', [ApiCategoryController::class, 'getSubcategory']);
Route::get('/childcategory', [ApiCategoryController::class, 'getChildcategory']);
