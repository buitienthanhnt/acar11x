<?php

namespace Thanhnt\Amuaglobal\Services;

use Thanhnt\Amuaglobal\Api\CartApi;
use Thanhnt\Amuaglobal\Api\OrderApi;
use Thanhnt\Amuaglobal\Models\ExpectOrder;

class CheckMoneyService
{
	public function __construct(
		protected OrderApi $orderApi,
		protected CartApi $cartApi,
	) {
		// throw new \Exception('Not implemented');
	}
	/**
	 * @param array{status: string, cart_type: string, currency_code: string, 
	 * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_url?: string}, 
	 * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
	 * 	customer_info?: array{name: string, email: string, phone: string}, 
	 * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
	 * 	shipping_address?: array{name: string, phone: string, location: string}, 
	 * 	expect_order?: string, cart_total: float, on_payment_order?: array{ token?: string, id?: string,}, on_payment?: string,
	 * } $cartParams
	 * @return ExpectOrder
	 */
	public function checkout($cartParams)
	{
		return $this->createOrder($cartParams);
	}

	/**
	 * @param array{status: string, cart_type: string, currency_code: string, 
	 * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_url?: string}, 
	 * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
	 * 	customer_info?: array{name: string, email: string, phone: string}, 
	 * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
	 * 	shipping_address?: array{name: string, phone: string, location: string}, 
	 * 	expect_order?: string, cart_total: float, on_payment_order?: array{ token?: string, id?: string,}, on_payment?: string,
	 * } $cartParams
	 * @return ExpectOrder
	 */
	protected function createOrder($cartParams)
	{
		/**
		 * create expect order
		 * must for checkout action
		 */
		$expect_order = $this->orderApi->createExpectOrderByCart($cartParams);
		/**
		 * update expect order in cart
		 * must for checkout action and success order
		 */
		$this->cartApi->updateExpectOrder($expect_order->id);
		return $expect_order;
	}
}
