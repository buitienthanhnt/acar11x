<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Acarglobal\Controllers\AcarController;

/**
 * use __invoke function in controller
 * controller use __invoke when the controller has one action
 * define route not input controller function the process will use __invoke function in controller
 */
// Route::get('acar', AcarController::class,);

Route::get('add-car', [AcarController::class, 'addCar']);

Route::prefix('acar')->group(function () {
	Route::get('/', [AcarController::class, 'index'])->name('acar.home');

	Route::get('setting', [AcarController::class, 'setting']);

	Route::post('setting-store', [AcarController::class, 'settingStore']);

	Route::post('apply-vat/{id}', [AcarController::class, 'applyVat'])->name('acar.apply.vat');

	Route::get('xe-vao', [AcarController::class, 'import']);

	Route::get('car-list', [AcarController::class, 'carList']);

	Route::get('car-history/{key}', [AcarController::class, 'carHistory'])->name('car.history');

	Route::post('import-car', [AcarController::class, 'register']);

	Route::get('car_fix/{id}', [AcarController::class, 'carFixDetail'])->name('car.fix.detail');

	Route::post('add-activity', [AcarController::class, 'addActivity']);

	Route::put('update-status/{id}', [AcarController::class, 'updateStatus']);

	Route::delete('remove-activity/{id}', [AcarController::class, 'deleteActivity'])->name('activities.destroy');

	Route::get('lenh-sua-chua/{car_fix}', [AcarController::class, 'xuatLenh']);

	Route::get('hoa-don/{car_fix}', [AcarController::class, 'xuatHoaDon']);

	Route::get('thong-ke', [AcarController::class, 'thongke']);

	Route::get('cham-cong', [AcarController::class, 'chamCong']);

	Route::get('work-history', [AcarController::class, 'workHistory']);

	Route::post('add-employee', [AcarController::class, 'addEmployee']);

	Route::post('save-work-time', [AcarController::class, 'saveWorkTime']);

	Route::delete('car-fix-delete/{id}', [AcarController::class, 'removeCarFix'])->name('car.fix.remove');

	Route::get('phu-tung', [AcarController::class, 'phuTung']);

	// Route::get('product/create-product', [AcarController::class, 'createPhuTung']);
});
