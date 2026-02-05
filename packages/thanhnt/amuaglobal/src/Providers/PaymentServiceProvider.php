<?php

namespace Thanhnt\Amuaglobal\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Stripe\Stripe;

final class PaymentServiceProvider extends ServiceProvider
{
	public function boot()
	{
		/**
		 * define for cashier stripe
		 */
		Cashier::useCustomerModel(User::class);
		// Cashier::calculateTaxes();

		/**
		 * config api key for stripe library
		 */
		Stripe::setApiKey(env('STRIPE_SECRET'));
	}
}
