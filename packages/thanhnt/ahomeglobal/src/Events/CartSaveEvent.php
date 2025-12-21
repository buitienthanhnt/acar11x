<?php

namespace Thanhnt\Ahomeglobal\Events;

final class CartSaveEvent
{
	/**
	 * @var array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}} $cart
	 */
	public $cart;

	public function __construct(
		array $cart
	) {
		$this->cart = $cart;
		// throw new \Exception('Not implemented');
	}
}
