<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('agame')->group(function () {
	Route::get('/', function () {
		return view('agameglobal::welcome');
	})->name('agameglobal.welcome');

	Route::get('bam-gio-don', function () {
		return Inertia::render('Agameglobal/BamGioDon');
	})->name('agameglobal.bam.gio.don');
});
