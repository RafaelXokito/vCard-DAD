<?php

use App\Http\Controllers\api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//CATEGORIES
Route::get('categories', [CategoryController::class, 'getCategories']);

Route::get('categories/{category}', [CategoryController::class, 'getCategory']);

Route::get('categories/{vcard}/vcard', [CategoryController::class, 'getCategoriesByVcard']);

Route::get('categories/{transaction}/category', [CategoryController::class, 'getCategoryByTransaction']);

//TRANSACTIONS
Route::get('transactions/{category}/transactions', [CategoryController::class, 'get']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
