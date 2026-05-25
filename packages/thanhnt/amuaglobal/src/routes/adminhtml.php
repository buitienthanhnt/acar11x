<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Amuaglobal\Controllers\AmuaController;

Route::prefix('adminhtml')->group(function () {
	Route::get('clear-cache', [AmuaController::class, 'clearCache']);

	Route::prefix('product')->group(function () {

		Route::get('/', [AmuaController::class, 'listProduct']);

		Route::get('/create', [AmuaController::class, 'createProduct']);

		Route::post('/store', [AmuaController::class, 'storeProduct']);

		Route::get('/detail/{id}', [AmuaController::class, 'detailProduct']);
	});
});
