<?php

use Illuminate\Support\Facades\Route;

Route::prefix('amua-api')->group(function () {
	Route::get('file-manage', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'fileManage']);

	Route::get('dir-structure', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'dirStructure']);
});
