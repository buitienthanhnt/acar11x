<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AuthController;

Route::prefix('ahome')->group(function () {
	/**
	 * default route for ahome route
	 */
	Route::get('/', [AhomeController::class, 'homePage']);

	Route::any('homes', [AhomeController::class, 'homePage']);

	Route::any('home-detail/{home}', [AhomeController::class, 'homeDetail'])->name('home.detail');

	Route::get('rooms/{home?}', [AhomeController::class, 'listRoom']);

	Route::get('orders', [AhomeController::class, 'listOrder']);

	Route::get('login', [AuthController::class, 'loginPage']);

	Route::post('login', [AuthController::class, 'loginAction'])->name('login');

	Route::prefix('test')->group(function () {
		Route::get('filter-order', [AhomeController::class, 'orderFiler']);

		Route::get('active-rooms', [AhomeController::class, 'activeRoom']);
	});
});

Route::any('checkout', [AhomeController::class, 'checkout'])->name('checkout');

Route::any('order-success', [AhomeController::class, 'orderSuccess'])->name('order.success');

Route::get('account-create', [AuthController::class, 'createAccount']);

Route::post('account-register', [AuthController::class, 'registerAccount'])->name('register');