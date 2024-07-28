<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register')->name('auth.register');
        Route::post('login', 'login')->name('auth.login');
        Route::post('refresh', 'refresh')->name('auth.refresh');
        Route::post('logout', 'logout')->name('auth.logout');
    });

    Route::controller(DepositController::class)->prefix('deposit')->group(function () {
        Route::get('/', 'index')->name('deposit.index');
        Route::get('/{deposit}', 'view')->name('deposit.view');
        Route::post('/', 'store')->name('deposit.store');

        Route::patch('approve/{deposit}', 'approve')->name('deposit.approve');
        Route::patch('reject/{deposit}', 'reject')->name('deposit.reject');
    });

    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('/', 'index')->name('order.index');
        Route::post('/', 'store')->name('order.store');
    });

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'index')->name('profile.index');
    });
});
