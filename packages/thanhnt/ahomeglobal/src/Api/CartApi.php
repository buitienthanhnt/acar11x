<?php

namespace Thanhnt\Ahomeglobal\Api;

use Exception;
use Illuminate\Contracts\Session\Session;
use Thanhnt\Ahomeglobal\Events\CartSaveEvent;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Repository\OrderRepository;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class CartApi
{
	const CART_KEY = 'cart';

	public function __construct(
		protected Session $session,
		protected Order $order,
		protected OrderTime $orderTime,
		protected Room $room,
		protected DateTimeHelper $dateTimeHelper,
		protected OrderRepository $orderRepository,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * @return array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}}|null
	 */
	public function getCart()
	{
		return $this->session->get(self::CART_KEY);
	}

	/**
	 * define function for create new order.
	 * @param string[] $dateValues
	 * @param int $home
	 * @param int $room
	 * @return array|null
	 * @throws Exception
	 */
	public function addCart($dateValues, $home = null, $room = null)
	{
		/**
		 * get expect order can be add to cart.
		 */
		$expectOrderData = $this->orderRepository->getExpectOrder($dateValues, $home, $room);
		if (!$expectOrderData) {
			throw new Exception('the input date or home or room not active');
			return null;
		}

		/**
		 * @var array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: string[], total_price: float} $expectOrder
		 */
		$expect_order = $expectOrderData['expect_order'];
		/**
		 * @var mixed $expert_room
		 */
		$expert_room = $expectOrderData['expect_room'];

		$currency_code = config('ahomeglobal.currency_code');

		/**
		 * @var array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}} $cartData
		 */
		$cartData = [
			...$expect_order,
			"currency_code" => $currency_code,
			"item" => [
				"name" => $expert_room->{RoomInterface::TITLE},
				"description" => $expert_room->{RoomInterface::DESCRIPTION},
				"price" => $expert_room->price,
				"quantity" => $this->orderRepository->dateCount($dateValues), // date selected count(need for caculate total price)
				"image_url" => "https://amuaglobal.icu/storage/files/upload/nguoi-sinh-thang-am-lich-nay-co-the-xoay-chuyen-cuoc-doi-thanh-dat-nhu-y-hinh-4.jpg", //$expert_room->{RoomInterface::IMAGE_PATH},
				"url" => route('home.detail', ['home' => $home, 'room' => $expert_room->{RoomInterface::ID}]),
				'unit_amount' => [ // <--- THIS OBJECT IS REQUIRED
					'currency_code' => $currency_code,
					'value' => $expert_room->price,
				],
			],
			"customer_info" => null, // array{name: string, email: string, phone: string}
			"on_payment_order" => null,      // array{token: string, id: string,}
		];

		/**
		 * add data to session
		 * and return status of process.
		 */
		return $this->saveCart($cartData) ? $this->getCart() : null;
	}


	/**
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}} $params
	 * @return array{amount: array{currency_code: string, value: float, breakdown: array{item_total: array{currency_code: string, value: float,}}}, items: array{}}
	 */
	public function formatCartToPaypalParam(array $params)
	{
		$item = $params['item'];
		$data = [
			"purchase_units" => [
				[
					"amount" => [
						"currency_code" => $params['currency_code'],
						"value" => $params['total_price'],
						"breakdown" =>  [
							"item_total" =>  [
								"currency_code" => $params['currency_code'],
								"value" => $params['total_price'],
							],
						]
					],
					"items" => [
						[
							"name" => $item['name'],
							"description" => $item['description'],
							"unit_amount" => [ // require_field
								"currency_code" => $params['currency_code'],
								"value" => $item['price'],
							],
							"quantity" => $item['quantity'],
							"image_url" => $item['image_url'],
							"url" => $item['url'],
						],
					]
				]
			]
		];
		return $data['purchase_units'][0];
	}

	/**
	 * save cart data to session dispatch.
	 */
	public function saveCart(array $cartData)
	{
		if (empty($cartData)) {
			return false;
		}
		$this->session->put(self::CART_KEY, $cartData);

		/**
		 * dispatch event save cart
		 */
		\Illuminate\Support\Facades\Event::dispatch(new CartSaveEvent($cartData));
		return true;
	}

	/**
	 * @param array{name: string, email: string, phone: string} $customerInfo
	 * @return void
	 * @throws Exception
	 */
	public function updateCartCustomer($customerInfo)
	{
		if ($this->getCart()) {
			$this->session->put(self::CART_KEY . '.customer_info', $customerInfo);
			return;
		}
		throw new Exception("cart data not found", 1);
	}

	/**
	 * clear cart
	 * @return void
	 */
	public function clearCart()
	{
		$this->session->forget(self::CART_KEY);
	}

	/**
	 * get cart customer
	 * @return array{name: string, email: string, phone: string}|null
	 */
	public function getCartCustomer()
	{
		return $this->session->get(self::CART_KEY . '.customer_info');
	}

	/**
	 * update cart on order
	 * @param array $onPaymentOrder
	 */
	public function updateCartOnOrder($onPaymentOrder)
	{
		if ($this->getCart()) {
			$this->session->put(self::CART_KEY . '.on_payment_order', $onPaymentOrder);
			return;
		}
		throw new Exception("cart data not found", 1);
	}
}
