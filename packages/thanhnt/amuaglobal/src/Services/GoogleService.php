<?php

namespace Thanhnt\Amuaglobal\Services;

use Illuminate\Http\Request;
use Stripe\Charge;

class GoogleService
{
	public function __construct(
		protected Request $request,
	) {
		// throw new \Exception('Not implemented');
	}
	public function checkout()
	{
		// 1. Get payment data from request
		$paymentData = $this->request->all();
		$token = $paymentData['paymentMethodData']['tokenizationData']['token'];

		// 2. Process with Payment Gateway (e.g., Stripe

		try {
			$charge = Charge::create([
				'amount' => 1000, // Amount in cents
				'currency' => 'usd',
				'description' => 'Example Charge',
				'source' => $token, // Token from Google Pay
			]);

			dd($charge);

			return response()->json(['success' => true, 'charge' => $charge]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()]);
		}
	}
}
