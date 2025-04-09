<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

/**
 *会員登録・ログイン・ログアウト
 */
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/thanks', function () {
        return view('thanks');
    })->name('thanks');
});

// 飲食店一覧ページ
Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/restaurants/{restaurant}', [ShopController::class, 'showDetail'])->name('restaurants.detail');

/**
 * 認証後
 */
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ShopController::class, 'showMypage'])->name('mypage');
    Route::post('/favorites/add/{restaurantId}', [ShopController::class, 'addFavorite'])->name('favorites.add');
    Route::post('/favorites/remove/{restaurantId}', [ShopController::class, 'removeFavorite'])->name('favorites.remove');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservation/complete', [ReservationController::class, 'reservationComplete'])->name('reservation.complete');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{reservation}/update', [ReservationController::class, 'update'])->name('reservations.update');
});

/**
 * メール認証
 */
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');


