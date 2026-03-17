<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('agame')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Agameglobal/Home');
    })->name('agameglobal.home');

    Route::get('bam-gio-don', function () {
        return Inertia::render('Agameglobal/BamGioDon');
    })->name('agameglobal.bam.gio.don');

    Route::get('dem-nguoc', function () {
        return Inertia::render('Agameglobal/DemNguoc');
    })->name('agameglobal.dem.nguoc');

    Route::get('bam-gio', function () {
        return Inertia::render('Agameglobal/BamGioChuyenTiep');
    });
});
