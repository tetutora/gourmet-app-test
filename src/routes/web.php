<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/**
 *会員登録・ログイン・ログアウト
 */
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister');
    Route::post('/register', 'register');
    Route::get('/thanks', function () {
        return view('thanks');
    })->name('thanks');
    Route::get('/login', 'showLogin');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout');
});

/**
 *認証後
 */
Route::middleware('auth')->group(function () {
    // 飲食店一覧ページ
    Route::get('/', [ShopController::class, 'index'])->name('index');
});