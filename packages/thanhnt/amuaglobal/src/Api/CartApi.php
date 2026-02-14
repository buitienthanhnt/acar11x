<?php

namespace Thanhnt\Amuaglobal\Api;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Helper\DateTimeHelper;

final class CartApi
{
	const SESSION_CART = 'amua_cart';

	const CART_TOTAL = 'total_price';
	const STATUS = 'status';
	const TYPE = 'type';
	const CURRENCY_CODE = 'currency_code';
	const CART_ITEM = 'cart_item';
	const ORDER_TIME = 'order_time';
	const ORDER_DATE_FROM = 'date_from';
	const ORDER_DATE_TO = 'date_to';
	const ORDER_SELECTED_TIME = 'selected_time';

	const CUSTOMER_INFO = 'customer_info';
	const SHIPPING_METHOD = 'shipping_method';
	const SHIPPING_ADDRESS = 'shipping_address';
	const EXPECT_ORDER = 'expect_order';
	const ON_PAYMENT_ORDER = 'on_payment_order';
	const ON_PAYMENT = 'on_payment';

	public function __construct(
		protected Session $session,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param Model $product
	 * @param string[] $dateSelected
	 * @param int $qty
	 */
	public function addToCart(
		Model $product,
		array $dateSelected,
		int $qty = 1,
	) {
		$cartData = $this->caculateCartData($product, $dateSelected, $qty);
		return $this->saveCart($cartData);
	}

	/**
	 * caculate cart data
	 * @param Model $product
	 * @param string[] $dateSelected
	 * @param int $qty
	 * @return array
	 */
	protected function caculateCartData(
		Model $product,
		array $dateSelected,
		int $qty = 1,
	) {
		$cartItemData = $this->formatCartItem($product);
		$cartData =  [
			self::STATUS => 'pending',
			self::TYPE => 'product',
			self::CART_ITEM => [...$cartItemData, 'qty' => $qty],
			self::CURRENCY_CODE => config('amuaglobal.currency_code'),
			self::ORDER_TIME => [
				self::ORDER_DATE_FROM => min($dateSelected),
				self::ORDER_DATE_TO => max($dateSelected),
				self::ORDER_SELECTED_TIME => $this->dateTimeHelper->getListDates($dateSelected, 'Y-m-d'),
			],
			self::CART_TOTAL => $cartItemData['price'] * $qty,
			// 'shipping_method' => [
			// 	'type' => 'free',
			// 	'shipping_cost' => 0,
			// ],
			// 'shipping_address' => [
			// 	'name' => null,
			// 	'email' => null,
			// 	'location' => null,
			// ],
			// 'payment_method' => [
			// 	'type' => 'stripe',
			// 	'token' => null,
			// 	'id' => null,
			// ],
			// 'customer_info' => [
			// 	'name' => null,
			// 	'email' => null,
			// 	'phone' => null,
			// ],
			// 'expect_order' => null,
		];

		$currentCart = $this->getCart();
		/**
		 * caculate item qty
		 */
		// $this->caculateQty($cartData);
		/**
		 * caculate total price
		 */
		$this->calculateTotalPrice($cartData);
		return $cartData;
	}

	/**
	 * format cart item date
	 * @param Model $product
	 * @return array{id: int, image_url: string, name: string, price: float, qty: int, description: string, url: string}
	 */
	protected function formatCartItem($product)
	{
		return [
			'id' => $product->id,
			'image_url' => $product->image_path,
			'name' => $product->name,
			'price' => $product->price,
			'qty' => 1,
			'description' => $product->description,
			'url' => $product?->url,
		];
	}

	/**
	 * caculator and update total for cart after
	 * update shipping method.
	 * @return void
	 */
	protected function updateTotalPrice()
	{
		$cart = $this->getCart();
		$this->calculateTotalPrice($cart);
		/**
		 * caculate cart total price
		 */
		$this->updateCartByKey(self::CART_TOTAL, $cart[self::CART_TOTAL]);
	}

	/**
	 * @param array $cartData
	 * @return void
	 */
	protected function calculateTotalPrice(&$cartData)
	{
		$toTalPrice = 0.0;

		/**
		 * price by cart item
		 */
		if (isset($cartData[self::CART_ITEM])) {
			$toTalPrice += $cartData[self::CART_ITEM]['price'] * $cartData[self::CART_ITEM]['qty'] * count($cartData[self::ORDER_TIME][self::ORDER_SELECTED_TIME]);
		}

		/**
		 * price by shipping
		 */
		if (isset($cartData[self::SHIPPING_METHOD])) {
			$toTalPrice += $cartData[self::SHIPPING_METHOD]['shipping_cost'];
		}

		$cartData[self::CART_TOTAL] = $toTalPrice;
	}

	/**
	 * @param array $cartData
	 * @return void
	 */
	protected function caculateQty(&$cartData)
	{
		switch ($cartData[self::TYPE]) {
			case 'book':
				break;
			case 'home':
				break;
			case 'product':
			default:
				$cartData[self::CART_ITEM]['qty'] = $cartData[self::CART_ITEM]['qty'];
				break;
		}
	}

	/**
	 * update shipping method for cart
	 * @param string $shippingMethod
	 * @return void
	 */
	public function updateShippingMethod($shippingMethod)
	{
		$shippingMethodList = config('amuaglobal.shipping_method');
		/**
		 * find value in array: https://www.php.net/manual/en/function.array-find.php
		 */
		$shippingMethod = array_find($shippingMethodList, fn($method) => $method['key'] === $shippingMethod);
		$this->updateCartByKey(self::SHIPPING_METHOD, $shippingMethod);
		/**
		 * update total price update shipping method
		 */
		$this->updateTotalPrice();
	}

	/**
	 * @param array $shippingAddress
	 * @return void
	 */
	public function updateShippingAddress($shippingAddress)
	{
		$this->updateCartByKey(self::SHIPPING_ADDRESS, $shippingAddress);
	}

	/**
	 * update payment method
	 * @param string $paymentMethod
	 * @return void
	 */
	public function updatePaymentMethod(string $paymentMethod)
	{
		$this->updateCartByKey(self::ON_PAYMENT, $paymentMethod);
	}

	/**
	 * update cart customer info
	 * @param array $customerInfo
	 * @return void
	 */
	public function updateCustomerInfo($customerInfo)
	{
		$this->updateCartByKey(self::CUSTOMER_INFO, $customerInfo);
	}

	/**
	 * @param string $expectOrder
	 * @return void
	 */
	public function updateExpectOrder(string $expectOrder)
	{
		$this->updateCartByKey(self::EXPECT_ORDER, $expectOrder);
	}

	/**
	 * chua chay thu, can kiem tra them
	 */
	public function getExpectOrder()
	{
		return $this->getCartValueByKey(self::EXPECT_ORDER);
	}

	public function clearExpectOrder()
	{
		$this->session->pull(self::EXPECT_ORDER, null);
	}

	public function checkout() {}

	/**
	 * get cart
	 * @return array{status: string, cart_type: string, currency_code: string, 
	 * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_path?: string}, 
	 * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
	 * 	customer_info?: array{name: string, email: string, phone: string}, 
	 * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
	 * 	shipping_address?: array{name: string, phone: string, location: string}, 
	 * 	expect_order?: string, total_price: float, on_payment_order: array{ token?: string, id?: string,}, on_payment?: string,
	 * }	 
	 */
	public function getCart()
	{
		// /** @var array<string, MessageBag> $bags */
		return $this->session->get($this->getCartKey());
	}

	/**
	 * save cart
	 * @param array $cartData
	 */
	public function saveCart($cartData)
	{
		/**
		 * method: put is update not add more
		 * method: push is add more so when get valuecan be array
		 */
		session()->put($this->getCartKey(), $cartData);
		return $this->getCart();
	}

	/**
	 * update cart by key
	 * @param string $key
	 * @param mixed $value
	 * @return array
	 */
	public function updateCartByKey(string $key, $value)
	{
		if (!$this->getCart()) {
			return;
		}
		$this->session->put($this->getCartKey() . '.' . $key, $value);
		return $this->getCart();
	}

	/**
	 * get cart data by key
	 * @param string $key
	 * @return mixed
	 */
	public function getCartValueByKey(string $key)
	{
		return $this->session->get($this->getCartKey() . '.' . $key);
	}

	/**
	 * clear cart
	 * @return void
	 */
	public function clearCart()
	{
		$this->session->forget($this->getCartKey());
	}

	/**
	 * @return string
	 */
	private function getCartKey()
	{
		return config('amuaglobal.cart_key') ?? self::SESSION_CART;
	}
}
