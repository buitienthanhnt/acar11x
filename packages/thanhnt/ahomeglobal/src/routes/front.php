<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeTestController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AuthController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\CheckoutController;

Route::prefix('ahome')->group(function () {
	/**
	 * default route for ahome route
	 */
	Route::get('/', [AhomeController::class, 'homePage']);

	Route::any('homes', [AhomeController::class, 'home']);

	Route::any('home-detail/{home}', [AhomeController::class, 'homeDetail'])->name('home.detail');

	Route::get('rooms/{home?}', [AhomeController::class, 'listRoom']);

	Route::get('orders', [AhomeController::class, 'listOrder']);

	Route::get('login', [AuthController::class, 'loginPage']);

	Route::post('login', [AuthController::class, 'loginAction'])->name('login');

	Route::prefix('test')->group(function () {
		Route::get('filter-order', [AhomeController::class, 'orderFiler']);

		Route::get('active-rooms', [AhomeController::class, 'activeRoom']);

		Route::get('order-email', [AhomeController::class, 'sendMailOrder']);

		Route::get('inertia-stream', [AhomeController::class, 'stream']);

		Route::get('inertia-stream2', function () {
			return Inertia::render('Screen/TestScreen/Stream');
		});

		Route::get('/view-content', [AhomeTestController::class, 'viewTextContent']);

		Route::get('/view-stream', [AhomeTestController::class, 'optimateStream']);
	});
});

/**
 * checkout page about 2 step order-info and order-payment
 * show cart-info, set order-info
 */
Route::any('checkout', [CheckoutController::class, 'checkout'])->name('checkout');

/**
 * checkout by payment paypal, stripe
 */
Route::post('checkout-payment', [CheckoutController::class, 'paymentOrder'])->name('checkout.payment');

// Route::get('create-checkout-session', [CheckoutController::class, 'paymentOrderStripe']);

/**
 * order success after payment examp: paypal,stripe
 */
Route::any('checkout-success', [CheckoutController::class, 'checkoutSuccess'])->name('checkout.success');

Route::get('account-create', [AuthController::class, 'createAccount']);

Route::post('account-register', [AuthController::class, 'registerAccount'])->name('register');
