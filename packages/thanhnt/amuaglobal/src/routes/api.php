<?php

use Illuminate\Support\Facades\Route;

Route::prefix('amua-api')->group(function () {
	Route::get('file-manage', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'fileManage']);

	Route::post('upload-file', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'uploadFile']);

	Route::post('add-folder', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'addFolder']);

	Route::delete('remove-folder', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'removeFolder']);

	Route::get('dir-structure', [\Thanhnt\Amuaglobal\Controllers\AmuaController::class, 'dirStructure']);
});
