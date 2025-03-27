<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use Laravel\Fortify\Fortify;


// 会員登録・ログイン・ログアウト
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister');
    Route::post('/register', 'register');
    Route::get('/login', 'showLogin');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

// 認証後画面
Route::middleware('auth')->group(function () {
    // 飲食店一覧ページ
    Route::get('/', [ShopController::class, 'index'])->name('index');
});