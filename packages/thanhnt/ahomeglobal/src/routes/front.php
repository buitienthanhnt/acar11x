<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeController;

Route::prefix('ahome')->group(function () {
	/**
	 * default route for ahome route
	 */
	Route::get('/', [AhomeController::class, 'default']);

	Route::get('home', [AhomeController::class, 'home']);
});
