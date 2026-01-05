<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Thanhnt\Abookglobal\Controllers\AbookController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AhomeTestController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\AuthController;
use Thanhnt\Ahomeglobal\Controllers\Frontend\CheckoutController;

Route::prefix('abook')->group(function () {
	Route::get('/test', [AbookController::class, 'index']);

	Route::get('/detail/{id}', [AbookController::class, 'bookDetail']);
});
