<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/**
 *会員登録・ログイン・ログアウト
 */
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

// 飲食店一覧ページ
Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/restaurants/{id}', [ShopController::class, 'showDetail'])->name('restaurants.detail');

// 認証後画面
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ShopController::class, 'showMypage'])->name('mypage');
    Route::post('/favorites/add/{restaurantId}', [ShopController::class, 'addFavorite'])->name('favorites.add');
    Route::post('/favorites/remove/{restaurantId}', [ShopController::class, 'removeFavorite'])->name('favorites.remove');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservation', [ReservationController::class, 'reservationComplete'])->name('reservation.complete');
});