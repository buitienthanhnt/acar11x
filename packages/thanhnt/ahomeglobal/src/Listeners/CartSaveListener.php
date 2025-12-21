<?php

namespace Thanhnt\Ahomeglobal\Listeners;

use Illuminate\Support\Facades\Log;
use Thanhnt\Ahomeglobal\Events\CartSaveEvent;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class CartSaveListener
{

	/**
	 * well done for run event
	 */
	public function handle(CartSaveEvent $event)
	{
		/**
		 * @var array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}} $cart
		 */
		$cart = $event->cart;
		if (empty($cart)) {
			Log::alert('empty cart!');
			return;
		}
		Log::info('event listener: add new cart with room id: ' . $cart['room_id']);
	}
}
