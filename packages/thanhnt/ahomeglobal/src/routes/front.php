<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeController;

Route::prefix('ahome')->group(function () {
	/**
	 * default route for ahome route
	 */
	Route::get('/', [AhomeController::class, 'homePage']);

	Route::any('homes', [AhomeController::class, 'homePage']);

	Route::any('home-detail/{home}', [AhomeController::class, 'homeDetail']);

	Route::get('rooms/{home?}', [AhomeController::class, 'listRoom']);

	Route::get('orders', [AhomeController::class, 'listOrder']);

	Route::prefix('test')->group(function () {
		Route::get('filter-order', [AhomeController::class, 'orderFiler']);

		Route::get('active-rooms', [AhomeController::class, 'activeRoom']);
	});
});
