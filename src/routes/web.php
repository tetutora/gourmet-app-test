<?php

use Illuminate\Support\Facades\Route;
use App\Constants\Constants;
use App\Http\Controllers\{
    AdministratorController,
    AuthController,
    PaymentController,
    ReservationController,
    RepresentativeController,
    ReviewController,
    ShopController,
    QRCodeController,
    VerificationController
};

/**
 * 認証系
 */
Route::controller(AuthController::class)->group(function () {
    Route::prefix('')->group(function () {
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register');
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/thanks', fn() => view('thanks'))->name('thanks');
    });
});

/**
 * 一般公開ページ
 */
Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/restaurants/{restaurant}', [ShopController::class, 'showDetail'])->name('restaurants.detail');

/**
 * 利用者ページ（ログイン必須）
 */
Route::middleware('auth')->group(function () {
    // マイページとお気に入り
    Route::prefix('mypage')->group(function () {
        Route::get('/', [ShopController::class, 'showMypage'])->name('mypage');
        Route::post('/favorites/add/{restaurantId}', [ShopController::class, 'addFavorite'])->name('favorites.add');
        Route::post('/favorites/remove/{restaurantId}', [ShopController::class, 'removeFavorite'])->name('favorites.remove');
    });

    // 予約
    Route::prefix('reservations')->group(function () {
        Route::post('/', [ReservationController::class, 'store'])->name('reservation.store');
        Route::get('/complete', [ReservationController::class, 'reservationComplete'])->name('reservation.complete');
        Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
        Route::post('/{reservation}/update', [ReservationController::class, 'update'])->name('reservations.update');
        Route::get('/{reservation}/qrcode', [ReservationController::class, 'showQRCode'])->name('reservations.qrcode');
    });

    // レビュー
    Route::prefix('reviews')->controller(ReviewController::class)->group(function () {
        Route::get('/create/{reservation}', 'create')->name('review.create');
        Route::post('/store/{reservation}', 'store')->name('review.store');
    });

    // QRコード
    Route::post('/generate-qrcode', [ReservationController::class, 'generateQRCode'])->name('generate.qrcode');

    // 決済
    Route::prefix('payment')->controller(PaymentController::class)->group(function () {
        Route::get('/', 'showPaymentForm')->name('payment.form');
        Route::post('/', 'processPayment')->name('payment.process');
    });
});

/**
 * 店舗代表者
 */
Route::middleware(['auth', "role:" . Constants::ROLE_REPRESENTATIVE])->prefix('representative')->group(function () {
    Route::get('/dashboard', [RepresentativeController::class, 'representativeDashboard'])->name('representative.dashboard');
    Route::get('/create', [RepresentativeController::class, 'create'])->name('representative.create');
    Route::post('/restaurants', [RepresentativeController::class, 'store'])->name('restaurants.store');
    Route::get('/restaurants', [RepresentativeController::class, 'index'])->name('representative.index');
    Route::get('/restaurants/{restaurant}/edit', [RepresentativeController::class, 'edit'])->name('restaurants.edit');
    Route::put('/restaurants/{restaurant}', [RepresentativeController::class, 'update'])->name('restaurants.update');
    Route::delete('/restaurants/{restaurant}', [RepresentativeController::class, 'destroy'])->name('restaurants.destroy');
    Route::post('/verify-qrcode', [ReservationController::class, 'verifyQRCode'])->name('verify.qrcode');
});

/**
 * 管理者
 */
Route::middleware(['auth', "role:" . Constants::ROLE_ADMIN])->prefix('administrator')->group(function () {
    Route::get('/dashboard', [AdministratorController::class, 'dashboard'])->name('administrator.dashboard');
    Route::get('/users/create', [AdministratorController::class, 'createRepresentative'])->name('administrator.create');
    Route::post('/users', [AdministratorController::class, 'storeRepresentative'])->name('administrator.store');
    Route::get('/mail', [AdministratorController::class, 'notifyForm'])->name('administrator.mail');
    Route::post('/notify/send', [AdministratorController::class, 'sendNotification'])->name('administrator.notify.send');
});

/**
 * メール認証
 */
Route::prefix('email')->group(function () {
    Route::get('/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});