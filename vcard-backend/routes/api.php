<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\PaymentTypeController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\VCardController;
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

Route::get('vcards/{vcard}/categories', [CategoryController::class, 'getCategoriesByVcard']);

Route::get('categories/{transaction}/category', [CategoryController::class, 'getCategoryByTransaction']);

Route::post('categories', [CategoryController::class, 'postCategory']);

Route::patch('categories/{category}', [CategoryController::class, 'putCategory']);

Route::delete('categories/{category}', [CategoryController::class, 'deleteCategory']);

//TRANSACTIONS
Route::get('transactions', [TransactionController::class, 'getTransactions']);

Route::get('transactions/{transaction}', [TransactionController::class, 'getTransaction']);

Route::get('transactions/{transaction}/pair', [TransactionController::class, 'getPairTransaction']);

Route::get('payment_types/{payment_type}/transactions', [TransactionController::class, 'getTransactionsByPaymentType']);

Route::get('vcards/{vCard}/transactions', [TransactionController::class, 'getTransactionsByVcard']);

Route::get('categories/{category}/transactions', [TransactionController::class, 'getTransactionsByCategory']);

Route::post('transactions', [TransactionController::class, 'postTransaction']);

Route::patch('transactions/{transaction}', [TransactionController::class, 'putTransaction']);

//Route::delete('transactions/{transaction}', [TransactionController::class, 'deleteTransaction']);

//PAYMENT_TYPES
Route::get('payment_types', [PaymentTypeController::class, 'getPaymentTypes']);

Route::get('transactions/{transaction}/payment_type', [PaymentTypeController::class, 'getPaymentTypeByTransaction']);

//VCARD
Route::get('vcards', [VCardController::class, 'getVcards'])->middleware('auth');

Route::get('vcards/{vcard}', [VCardController::class, 'getVcard']);

Route::post('vcards', [VCardController::class, 'postVcard']);

//USER

Route::post('signin', [AuthController::class, 'signin'])->name('login');

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth');





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
