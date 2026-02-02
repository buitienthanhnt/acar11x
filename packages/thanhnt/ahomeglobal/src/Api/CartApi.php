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
	const EXPECT_ORDER_KEY = 'expect_order';

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
	 * @return array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, expect_order: string}|null
	 */
	public function getCart()
	{
		return $this->session->get($this->getCartKey());
	}

	/**
	 * define function for create new order.
	 * @param array{dateValues: string[], home?: integer, room?: integer, qty?: integer} $params
	 * @return array|null
	 * @throws Exception
	 */
	public function addCart($params)
	{
		/**
		 * get expect order can be add to cart.
		 */
		$expectOrderData = $this->orderRepository->getExpectOrder($params['dateValues'], $params['home'], $params['room'] ?? null);
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
		 * @var array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, on_payment: string|null} $cartData
		 */
		$cartData = [
			...$expect_order,
			"currency_code" => $currency_code,
			"item" => [
				"name" => $expert_room->{RoomInterface::TITLE},
				"description" => $expert_room->{RoomInterface::DESCRIPTION},
				"price" => $expert_room->price * 1000,
				"quantity" => $this->orderRepository->dateCount($params['dateValues']), // date selected count(need for caculate total price)
				"image_url" => "https://amuaglobal.icu/storage/files/upload/nguoi-sinh-thang-am-lich-nay-co-the-xoay-chuyen-cuoc-doi-thanh-dat-nhu-y-hinh-4.jpg", //$expert_room->{RoomInterface::IMAGE_PATH},
				"url" => route('home.detail', ['home' => $params['home'], 'room' => $expert_room->{RoomInterface::ID}]),
				'unit_amount' => [ // <--- THIS OBJECT IS REQUIRED
					'currency_code' => $currency_code,
					'value' => $expert_room->price * 1000,  // price setup = 150(k vnd) so price * 1000 to vnd
				],
			],
			"customer_info" => null, // array{name: string, email: string, phone: string}
			"on_payment_order" => null,      // array{token: string, id: string,}  ()
			'on_payment' => null,            // paypal|stripe 
			// 'expect_order' => null,
			'qty' => $params['qty'] ?? 1,
		];

		/**
		 * keep some old cart data if exist
		 */
		if ($currentCart = $this->getCart()) {
			$cartData = [
				...$cartData,
				'customer_info' => $currentCart['customer_info'],
				// "on_payment_order" => $currentCart['on_payment_order'],
				"on_payment" => $currentCart['on_payment'],
				// "expect_order" => $currentCart['expect_order'],
			];
		}

		/**
		 * add data to session
		 * and return status of process.
		 */
		return $this->saveCart($cartData) ? $this->getCart() : null;
	}


	/**
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null} $params
	 * @return array{amount: array{currency_code: string, value: float, breakdown: array{item_total: array{currency_code: string, value: float,}}}, items: array{}}
	 */
	public function formatCartToPaypalParam(array $params)
	{
		$item = $params['item'];
		$data = [
			"purchase_units" => [
				[
					"amount" => [
						"currency_code" => 'USD' ?: $params['currency_code'],
						"value" => number_format($item['price'] / config('ahomeglobal.exchange_vnd'), 2) * $item['quantity'] +  number_format(14000 / config('ahomeglobal.exchange_vnd'), 2),
						"breakdown" =>  [
							"item_total" =>  [
								"currency_code" => 'USD' ?: $params['currency_code'],
								"value" => number_format($item['price'] / config('ahomeglobal.exchange_vnd'), 2) * $item['quantity'],
							],
							"shipping" => [
								"currency_code" => 'USD' ?: $params['currency_code'],
								"value" =>  number_format(14000 / config('ahomeglobal.exchange_vnd'), 2),
							]
						]
					],
					"items" => [
						[
							"name" => $item['name'],
							"description" => $item['description'],
							"unit_amount" => [ // require_field
								"currency_code" => 'USD' ?: $params['currency_code'],
								"value" => number_format($item['price'] / config('ahomeglobal.exchange_vnd'), 2),
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
		$this->session->put($this->getCartKey(), $cartData);

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
		if ($cart = $this->getCart()) {
			if ($cart['customer_info'] !== $customerInfo) {
				$this->session->put($this->getCartKey() . '.customer_info', $customerInfo);
			}
			return;
		}
		throw new Exception("cart data not found", 1);
	}

	/**
	 * clear cart
	 * @return void
	 */
	public function clearCartOrder()
	{
		$this->session->forget($this->getCartKey());
		$this->session->forget(self::EXPECT_ORDER_KEY);
	}

	/**
	 * get cart customer
	 * @return array{name: string, email: string, phone: string}|null
	 */
	public function getCartCustomer()
	{
		return $this->session->get($this->getCartKey() . '.customer_info');
	}

	/**
	 * update cart on order
	 * @param array $onPaymentOrder
	 */
	public function updateCartOnOrder($onPaymentOrder)
	{
		if ($this->getCart()) {
			$this->session->put($this->getCartKey() . '.on_payment_order', $onPaymentOrder);
			return;
		}
		throw new Exception("cart data not found", 1);
	}

	public function updateCart($cartData)
	{
		if ($this->getCart()) {
			/**
			 * put session is update
			 */
			$this->session->put($this->getCartKey(), $cartData);
			return;
		}
		throw new Exception("cart data not found", 1);
	}

	/**
	 * update cart by path
	 * @param string $key  ex expect_order
	 * @param mixed $value
	 * @return void
	 */
	public function updateByKey(string $key, $value)
	{
		if ($this->getCart()) {
			/**
			 * put session is update
			 */
			$this->session->put($this->getCartKey() . '.' . $key, $value);
			return;
		}
	}

	/**
	 * @param mixed $value
	 * @return void
	 */
	public function addCartValue($value)
	{
		/**
		 * push session id add to array value
		 */
		session()->push($this->getCartKey(), $value);
	}

	/**
	 * get expect order
	 * @return string|null
	 */
	public function getExpectOrder()
	{
		return $this->session->get(self::EXPECT_ORDER_KEY);
	}

	/**
	 * update expect order
	 * @param string $expectOrder
	 * @return void
	 */
	public function updateExpectOrder($expectOrder)
	{
		$this->session->put(self::EXPECT_ORDER_KEY, $expectOrder);
	}

	/**
	 * clear expect order
	 * @return void
	 */
	public function clearExpectOrder()
	{
		$this->session->forget(self::EXPECT_ORDER_KEY);
	}

	private function getCartKey()
	{
		return config('ahomeglobal.cart') ?? self::CART_KEY;
	}
}
