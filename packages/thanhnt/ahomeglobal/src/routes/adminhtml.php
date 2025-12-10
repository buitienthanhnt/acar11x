<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Ahomeglobal\Controllers\Adminhtml\OrderAdminController;

Route::prefix('ahome')->group(function (): void {

	Route::get('delete-order', [OrderAdminController::class, 'deleteOrders']);

	Route::get('delete-ordertime', [OrderAdminController::class, 'deleteOrderTime']);
});
