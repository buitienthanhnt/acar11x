<?php

use Illuminate\Support\Facades\Route;

Route::prefix('amua-api')->group(function () {
	Route::get('file-manage', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'fileManage']);

	Route::post('upload-file', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'uploadFile']);

	Route::get('dir-structure', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'dirStructure']);
});
