<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/accounts', [AccountController::class, 'store']);
    Route::get('/accounts/{account_number}', [AccountController::class, 'show']); // Get Account Details
    Route::put('/accounts/{account_number}', [AccountController::class, 'update']); // Update Account
    Route::delete('/accounts/{account_number}', [AccountController::class, 'delete']); // Delete Account
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions', [TransactionController::class, 'getTransactions']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});