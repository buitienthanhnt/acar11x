<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Abookglobal\Controllers\AbookController;

Route::prefix('abook')->group(function () {
	Route::get('/test', [AbookController::class, 'index']);

	Route::get('/detail/{id}', [AbookController::class, 'bookDetail'])->name('abook.detail');

	Route::get('/{alias}.htm', [AbookController::class, 'bookByCategory']);
});
