<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkout', [TransactionController::class, 'initiateCheckout']);
Route::get('/thank-you', [TransactionController::class, 'retrieveOrder']);
Route::put('/refund', [TransactionController::class, 'initiateRefund']);
Route::put('/void', [TransactionController::class, 'initiateVoid']);
Route::put('/verify', [TransactionController::class, 'initiateVerify']);
Route::put('/authorize', [TransactionController::class, 'initiateAuthorize']);
Route::put('/capture', [TransactionController::class, 'initiateCapture']);
