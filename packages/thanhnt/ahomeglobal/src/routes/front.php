<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeTestController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AuthController;

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

	Route::get('location/{location}', [AhomeController::class, 'homeLocation']);

	Route::post('login', [AuthController::class, 'loginAction'])->name('home.login');

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

Route::get('account-create', [AuthController::class, 'createAccount']);

Route::post('account-register', [AuthController::class, 'registerAccount'])->name('ahome.register');
