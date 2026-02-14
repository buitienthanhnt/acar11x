<?php

namespace Thanhnt\Amuaglobal\Services;

use ErrorException;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Thanhnt\Amuaglobal\Api\CartApi;
use Thanhnt\Amuaglobal\Api\OrderApi;
use Thanhnt\Amuaglobal\Models\ExpectOrder;

class StripeService
{

	protected $stripe;

	public function __construct(
		protected OrderApi $orderApi,
		protected CartApi $cartApi,
	) {
		$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
		$this->stripe = $stripe;
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param array{status: string, cart_type: string, currency_code: string, 
	 * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_url?: string}, 
	 * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
	 * 	customer_info?: array{name: string, email: string, phone: string}, 
	 * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
	 * 	shipping_address?: array{name: string, phone: string, location: string}, 
	 * 	expect_order?: string, total_price: float, on_payment_order?: array{ token?: string, id?: string,}, on_payment?: string,
	 * } $cartParams	 
	 * @return \Stripe\Checkout\Session
	 */
	public function checkout($cartParams)
	{

		/**
		 * check has exist old order
		 */
		if (isset($cartParams['on_payment_order']) && isset($cartParams['on_payment_order']['id'])) {
			/**
			 * update order
			 */
			return $this->updateCartItem($cartParams['on_payment_order']['id'], $cartParams);
		}

		/**
		 * create cart
		 */
		return $this->createCartItem($cartParams);
	}

	/**
	 * gán sản phẩm vào session của stripe.
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}} $params
	 * @return \Stripe\Checkout\Session|null
	 */
	public function createCartItem($params, $absolute = false)
	{
		/**
		 * should create new expert order
		 * then
		 * pass increment id of order to cart, and save to session, pass to checkout url.
		 */
		$expectOrder = $this->orderApi->createExpectOrderByCart($params);
		/**
		 * set expect order id to session
		 */
		$this->cartApi->updateExpectOrder($expectOrder->id);

		$cartItemData = $this->formatLineItem($params);
		// https://www.youtube.com/watch?v=J13Xe939Bh8
		try {
			/**
			 * add product to cart and return checkout object has url_checkout link
			 * https://docs.stripe.com/payments/checkout
			 * doc for create: https://docs.stripe.com/checkout/quickstart
			 * https://docs.stripe.com/api/checkout/sessions/create#create_checkout_session-line_items-price_data
			 */
			$checkout_session = Session::create($cartItemData)->all()->first();
			/**
			 * update cart session expect_order if create api success.
			 */
			$this->cartApi->updateCartByKey('on_payment_order.id', $checkout_session->id);
			return $checkout_session;
		} catch (ErrorException $e) {
			/**
			 * if error, delete expert order
			 */
			ExpectOrder::forceDestroy($expectOrder->id);
			$this->cartApi->clearExpectOrder();
			return null;
		}
	}

	/**
	 * @param string $cartItemId
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}, expect_order?: string} $cartParams
	 * @return \Stripe\Checkout\Session
	 */
	public function updateCartItem(string $cartItemId, array $cartParams)
	{
		try {
			/**
			 * expire old session
			 * https://docs.stripe.com/api/checkout/sessions/update
			 */
			$currentSession = Session::retrieve($cartItemId);
			if ($currentSession->status === 'open') {
				$currentSession->expire();
			}
			$cartItemData = $this->formatLineItem($cartParams);

			/**
			 * add product to cart and return checkout object has url_checkout link
			 * https://docs.stripe.com/payments/checkout
			 * doc for create: https://docs.stripe.com/checkout/quickstart
			 * https://docs.stripe.com/api/checkout/sessions/create#create_checkout_session-line_items-price_data
			 */
			$checkout_session = Session::create($cartItemData)->all()->first();
			/**
			 * allways update if exist expect order
			 * then update too cart session expect_order
			 */
			$exOrder = $this->orderApi->updateExpectOrder($this->cartApi->getExpectOrder(), $cartParams);
			$this->cartApi->updateCartByKey('on_payment_order.id', $exOrder->id);

			return $checkout_session;
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/**
	 * hàm này tải product và giá lên stripe dash board sau đó trả về thông tin giá và sản phẩm
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}} $cartParams
	 */
	public function addProduct(array $params)
	{

		$product = $this->stripe->products->create([
			'name' => 'product demo',
			'description' => 'Comfortable demo product',
		]);

		$price = $this->stripe->prices->create([
			'product' => $product->id,
			'unit_amount' => 19, // $20.00
			'currency' => 'usd',
		]);

		return "Product created: " . $product->id . " with Price: " . $price->id;
	}

	/**
	 * Calculate the orders total amount
	 */
	public function calculateTotal($price, $qty)
	{
		$total = $price * $qty;
		return $total;
	}


	/**
	 * Calculate the amount for stripe
	 */
	public function calculateOrderTotal($items)
	{
		$total = 0;
		//calculate the total amount of cart items
		foreach ($items as $item) {
			$total += $this->calculateTotal($item['product_price'], $item['quantity']);
		}
		return $total * 100;
	}

	public function stripeCharge()
	{
		$myCard = array('number' => '4242424242424242', 'exp_month' => 12, 'exp_year' => 2026);
		$charge = Charge::create(array('card' => $myCard, 'amount' => 700, 'currency' => 'usd'));
		echo $charge;
	}

	/**
	 * get stripe cart session
	 * @return \Stripe\Collection<Session> of ApiResources
	 */
	public function getCartSession()
	{
		$cart = Session::all();
		return $cart;
	}

	/**
	 * get stripe cart items
	 * @param string $cartItemId
	 * @return \Stripe\Collection<Session> of ApiResources
	 */
	public function getCartItems(string $cartItemId)
	{
		$alline_aitem = Session::allLineItems($cartItemId);
		return $alline_aitem;
	}

	/**
	 * @param array{status: string, cart_type: string, currency_code: string, 
	 * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_url?: string}, 
	 * 	order_time: array{date_from: string, date_to: string, selected_time: array[string]}, 
	 * 	customer_info?: array{name: string, email: string, phone: string}, 
	 * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
	 * 	shipping_address?: array{name: string, phone: string, location: string}, 
	 * 	expect_order?: string, total_price: float, on_payment_order?: array{ token?: string, id?: string,}, on_payment?: string,
	 * } $cartParams	 
	 * @return array{line_items: array{price_data: array{currency: string, product_data: array{name: string, images: array[string]}}[], mode: string, success_url: string, cancel_url: string}
	 */
	public function formatLineItem($cartParams)
	{
		$product = $cartParams['cart_item'];
		$quantity = $product['qty'] * count($cartParams['order_time']['selected_time']);
		/**
		 * doc: https://docs.stripe.com/api/checkout/sessions/create#create_checkout_session-line_items-price_data
		 * doc has shipping: https://docs.stripe.com/api/checkout/sessions/create?lang=php
		 */
		$item = [
			'price_data' => [
				'currency' => $cartParams['currency_code'],
				'product_data' => [
					'name' => $product['name'],
					'images' => ['https://amuaglobal.icu/storage/files/upload/nguoi-sinh-thang-am-lich-nay-co-the-xoay-chuyen-cuoc-doi-thanh-dat-nhu-y-hinh-4.jpg'],
				],
				'unit_amount' => $product['price'], // 60$ luwu ys chuyen doi theo ty gia
			],
			'quantity' => $quantity,
		];

		$formatData = [
			'line_items' => [$item],
			'mode' => 'payment',
			'shipping_options' => [
				[
					'shipping_rate_data' => [
						'display_name' => $cartParams['shipping_method']['name'],
						'fixed_amount' => [
							'amount' => $cartParams['shipping_method']['shipping_cost'],
							'currency' => $cartParams['currency_code'],
						],
						'type' => 'fixed_amount',
					]
				]
			],
		];

		/**
		 * config for stripe ui_mode
		 */
		if (config('amuaglobal.payment.stripe.ui_mode') === 'custom') {
			/**
			 * custom mode if online checkout with stripe
			 * pay onpage checkout
			 */
			$formatData['ui_mode'] = 'custom';
			$formatData['return_url'] = route('checkout.success', ['expect_order' => $this->cartApi->getExpectOrder(), 'type' => 'stripe',]);
		} elseif (config('amuaglobal.payment.stripe.ui_mode') === 'hosted') {
			/**
			 * default pay out redirect checkout page stripe
			 */
			$formatData['success_url'] =  route('checkout.success', ['expect_order' => $this->cartApi->getExpectOrder(), 'type' => 'stripe',]);
			$formatData['cancel_url'] = route('checkout', ['step' => 'payment']);
		}

		return $formatData;
	}
}
