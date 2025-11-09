<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\PageApiController;
use App\Http\Controllers\Frontend\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('related', [PageApiController::class, 'related']);

Route::get('page-by-ids', [PageApiController::class, 'pageByIds']);

Route::get('page-random', [PageApiController::class, 'pageRandom']);

Route::get('page-sugget/{id}', [PageApiController::class, 'pageSugget']);

Route::get('comments', [CommentController::class, 'index']);

Route::get('top-comment', [PageApiController::class, 'topComment']);

Route::get('top-page', [PageApiController::class, 'topPages']);

Route::get('center-categories', [CategoryApiController::class, 'centerCategories']);
