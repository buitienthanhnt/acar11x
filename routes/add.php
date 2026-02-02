<?php

use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayPalTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/dashboard', function () {
	return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('{category}.htm', [HomeController::class, 'category'])->name('category');

Route::get('status', [\App\Http\Controllers\Frontend\ContentController::class, 'listStatus']);

Route::get('{page:alias}.html', [HomeController::class, 'detail'])->name('detail')->missing(function (Request $request) {
	// Thông thường, phản hồi HTTP 404 sẽ được tạo nếu không tìm thấy mô hình liên kết ngầm. Tuy nhiên, bạn có thể tùy chỉnh hành vi này bằng cách gọi missing
	return Redirect::route('home');
});;

Route::get('tag/{value}', [HomeController::class, 'tag'])->name('tag');

Route::get('/list/{id?}', [HomeController::class, 'list'])->name("list"); //->middleware('link'); // middleware de su dung cho: Linkeys\UrlSigner\Facade\UrlSigner

Route::get('about', [HomeController::class, "about"])->name('about');

Route::get('account', [HomeController::class, 'account'])->name('account');

Route::get('docs', [HomeController::class, 'docs'])->name('docs');

Route::inertia('/langs', 'Screen/CategoryScreen/Language');

Route::post('/lang-setup', [HomeController::class, 'langSetup']);

Route::get('writer/{id}', [HomeController::class, 'writerDetail'])->name('writerDetail');

Route::prefix('comment')->group(function (): void {

	Route::post('add', [CommentController::class, 'store']);
});

Route::post('add-source', [\App\Http\Controllers\HomeController::class, 'addSource']);

Route::get('search/{query?}', [\App\Http\Controllers\TestController::class, 'remenberState'])->name('search'); //->where(['query' => '[a-z]+']);

Route::prefix('paypal')->group(function (): void {

	Route::any('create', [PayPalTestController::class, 'index'])->name('paypal.checkout');

	Route::any('update', [PayPalTestController::class, 'updateOrder']);

	Route::any('detail', [PayPalTestController::class, 'orderDetail']);

	// Route::any('capture', [PayPalTestController::class, 'capture']);
	// Route::any('approved', [PayPalTestController::class, 'approved']); // patchOrder
});

Route::prefix('stripe')->group(function () {
	Route::get('create-product', [
		PayPalTestController::class,
		'addStripeProduct'
	]);

	Route::get('payment', [
		PayPalTestController::class,
		'payment'
	]);

	Route::get('guest-payment', [
		PayPalTestController::class,
		'stripeGuestCart'
	]);
	// stripeCharge
	Route::get('charge', [
		PayPalTestController::class,
		'stripeCharge'
	]);
});
