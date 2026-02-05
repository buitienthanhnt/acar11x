<?php

use Illuminate\Support\Facades\Route;

/**
 * checkout page about 2 step order-info and order-payment
 * show cart-info, set order-info
 */
// Route::any('checkout', [\Thanhnt\Ahomeglobal\Controllers\Frontend\CheckoutController::class, 'checkout'])->name('checkout'); // use checkout action in ahomeglobal
Route::any('checkout', [\Thanhnt\Abookglobal\Controllers\CheckoutController::class, 'checkout'])->name('checkout'); // use checkout action in abookglobal

/**
 * checkout by payment paypal, stripe
 */
// Route::post('checkout-payment', [\Thanhnt\Ahomeglobal\Controllers\Frontend\CheckoutController::class, 'paymentOrder'])->name('checkout.payment');
Route::post('checkout-payment', [\Thanhnt\Abookglobal\Controllers\CheckoutController::class, 'checkoutAction'])->name('checkout.payment');

/**
 * order success after payment examp: paypal,stripe
 */
// Route::any('checkout-success', [\Thanhnt\Ahomeglobal\Controllers\Frontend\CheckoutController::class, 'checkoutSuccess'])->name('checkout.success');
Route::any('checkout-success', [\Thanhnt\Abookglobal\Controllers\CheckoutController::class, 'checkoutSuccess'])->name('checkout.success');

/**
 * test
 */
// Route::get('create-checkout-session', [\Thanhnt\Ahomeglobal\Controllers\Frontend\CheckoutController::class, 'paymentOrderStripe']);
