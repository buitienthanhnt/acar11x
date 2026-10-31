<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Amuaglobal\Controllers\AmuaController;

Route::prefix('adminhtml')->group(function () {
    Route::get('clear-cache', [AmuaController::class, 'clearCache']);

    Route::prefix('amua-product')->group(function () {

        Route::get('/', [\Thanhnt\Amuaglobal\Controllers\Admin\ProductController::class, 'listProduct']);

        Route::get('/create', [\Thanhnt\Amuaglobal\Controllers\Admin\ProductController::class, 'createProduct']);

        Route::post('/store', [\Thanhnt\Amuaglobal\Controllers\Admin\ProductController::class, 'storeProduct']);

        Route::get('/detail/{id}', [\Thanhnt\Amuaglobal\Controllers\Admin\ProductController::class, 'detailProduct']);
    });

    Route::prefix('amua-category')->group(function () {
        Route::get('/', [\Thanhnt\Amuaglobal\Controllers\Admin\CategoryController::class, 'listCategory']);

        Route::get('/create', [\Thanhnt\Amuaglobal\Controllers\Admin\CategoryController::class, 'createCategory']);

        Route::post('/store', [\Thanhnt\Amuaglobal\Controllers\Admin\CategoryController::class, 'storeCategory']);

        Route::get('edit/{id}', [\Thanhnt\Amuaglobal\Controllers\Admin\CategoryController::class, 'editCategory']);

        Route::put('update/{id}', [\Thanhnt\Amuaglobal\Controllers\Admin\CategoryController::class, 'updateCategory']);
    });
});
