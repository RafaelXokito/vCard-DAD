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
Route::middleware(['auth:api','can:accessCritial,App\Models\VCard'])->group(function () {
    //CATEGORIES
    Route::get('categories', [CategoryController::class, 'getCategories'])->middleware('can:viewAny,App\Models\Category');

    Route::get('categories/{category}', [CategoryController::class, 'getCategory'])->middleware('can:view,category');;

    Route::get('vcards/{vcard}/categories', [CategoryController::class, 'getCategoriesByVcard'])->middleware('can:view,vcard');

    Route::get('categories/{transaction}/category', [CategoryController::class, 'getCategoryByTransaction'])->middleware('can:view,transaction');

    Route::post('categories', [CategoryController::class, 'postCategory'])->middleware('can:store,App\Models\Category');;

    Route::patch('categories/{category}', [CategoryController::class, 'patchCategory'])->middleware('can:update,category');

    Route::delete('categories/{category}', [CategoryController::class, 'deleteCategory'])->middleware('can:viewAny,category');

    //TRANSACTIONS
    Route::get('transactions', [TransactionController::class, 'getTransactions'])->middleware('can:viewAny,App\Models\Transaction');

    Route::get('transactions/{transaction}', [TransactionController::class, 'getTransaction'])->middleware('can:view,transaction');

    Route::get('transactions/{transaction}/pair', [TransactionController::class, 'getPairTransaction'])->middleware('can:viewPair,App\Models\Transaction');

    Route::get('payment_types/{payment_type}/transactions', [TransactionController::class, 'getTransactionsByPaymentType'])->middleware('can:view,App\Models\PaymentType');

    Route::get('vcards/{vCard}/transactions', [TransactionController::class, 'getTransactionsByVcard'])->middleware('can:view,vCard');

    Route::get('categories/{category}/transactions', [TransactionController::class, 'getTransactionsByCategory'])->middleware('can:view,category');

    Route::post('transactions', [TransactionController::class, 'postTransaction'])->middleware('can:store,App\Models\Transaction');

    Route::patch('transactions/{transaction}', [TransactionController::class, 'patchTransaction']);

    //Route::delete('transactions/{transaction}', [TransactionController::class, 'deleteTransaction']);

    //PAYMENT_TYPES
    Route::get('payment_types', [PaymentTypeController::class, 'getPaymentTypes'])->middleware('can:viewAny,App\Models\PaymentType');

    Route::get('payment_types/{payment_type}', [PaymentTypeController::class, 'getPaymentType'])->middleware('can:view,App\Models\PaymentType');

    Route::get('transactions/{transaction}/payment_type', [PaymentTypeController::class, 'getPaymentTypeByTransaction']);

    //VCARD
    Route::get('vcards', [VCardController::class, 'getVcards']);

    Route::get('vcards/{vcard}', [VCardController::class, 'getVcard']);

    //USERS
    Route::get('users/me', [UserController::class, 'getMe']);

    Route::get('users/{user}', [UserController::class, 'getUser'])->middleware('can:view,user');

	Route::patch('users/{user}/password', [UserController::class, 'update_password'])->middleware('can:updatePassword,user');

    Route::patch('users/{user}', [UserController::class, 'patchUser'])->middleware('can:update,user');

    Route::patch('users/{user}/block', [UserController::class, 'blockUser'])->middleware('can:block,App\Models\User');

    Route::post('users/{user}/updateUserPhoto', [UserController::class, 'postUserPhoto'])->middleware('can:update,user');

    Route::patch('users/{user}/updatePassword', [UserController::class, 'patchPasswordUser'])->middleware('can:update,user');

    Route::patch('users/{user}/updateConfirmationCode', [UserController::class, 'patchConfirmationCodeUser'])->middleware('can:update,user');

    Route::delete('vcards/{vcard}/delete', [VCardController::class, 'remove'])->middleware('can:delete,vcard');

    Route::get('users', [UserController::class, 'getUsers'])->middleware('can:viewAny,App\Models\User');

    Route::post('vards/{vcard}/confirmationCode', [AuthController::class, 'confirmationCode'])->middleware('can:view,vcard');

});

Route::post('registerVCard', [AuthController::class, 'registerVCard']);

Route::get('vards/{vcard}/makeConfirmationPhoneNumber', [AuthController::class, 'makeConfirmationPhoneNumber']);//->middleware('can:view,vcard');

Route::post('vards/{vcard}/verifyConfirmationPhoneNumber', [AuthController::class, 'verifyConfirmationPhoneNumber']);//->middleware('can:view,vcard');

Route::get('vards/{vcard}/closeConfirmationPhoneNumber', [AuthController::class, 'cancelConfirmationPhoneNumber']);//->middleware('can:view,vcard');

Route::get('vards/{vcard}/checkConfirmationPhoneNumber', [AuthController::class, 'checkConfirmationPhoneNumber']);//->middleware('can:view,vcard');
//USER

Route::post('signin', [AuthController::class, 'signin'])->name('login');

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

