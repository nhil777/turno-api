<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('refresh', 'refresh');
        Route::post('logout', 'logout');
    });

    Route::controller(DepositController::class)->group(function () {
        Route::post('deposit', 'store');
    });
});
