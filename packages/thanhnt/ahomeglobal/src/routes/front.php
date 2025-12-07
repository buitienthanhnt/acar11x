<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeController;

Route::prefix('ahome')->group(function () {
	/**
	 * default route for ahome route
	 */
	Route::get('/', [AhomeController::class, 'default']);

	Route::get('home', [AhomeController::class, 'home']);

	Route::get('create-home', [AhomeController::class, 'createHome']);

	Route::get('list-home', [AhomeController::class, 'listHome']);

	Route::any('home-detail/{home}', [AhomeController::class, 'homeDetail']);

	Route::get('list-room/{home?}', [AhomeController::class, 'listRoom']);

	Route::get('list-order', [AhomeController::class, 'listOrder']);

});
