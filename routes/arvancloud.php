<?php

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\UserWalletController;
use Illuminate\Support\Facades\Route;


Route::prefix('voucher')->group(function () {
    Route::post('submit', [VoucherController::class, 'submit']);
    Route::post('report', [VoucherController::class, 'report']);
});

Route::prefix('wallet')->group(function () {
    Route::post('report', [UserWalletController::class, 'report']);
});
