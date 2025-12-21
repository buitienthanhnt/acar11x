<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\OrderApi;

final class CheckoutController extends Controller 
{
	public function __construct(
		protected OrderApi $orderApi,
	)
	{
		// throw new \Exception('Not implemented');
	}

	/**
	 * 
	 */
	public function checkout() {
		return Inertia::render('Ahomeglobal/Screens/Checkout');
	}

	public function checkoutSuccess() {
		return Inertia::render('Ahomeglobal/Screens/CheckoutSuccess');
	}

	public function paypalCheckout() {
		$cart = $this->orderApi->getCart();
	}
}
