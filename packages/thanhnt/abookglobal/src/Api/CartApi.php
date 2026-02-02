<?php

namespace Thanhnt\Abookglobal\Api;

use Illuminate\Contracts\Session\Session;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Abookglobal\Models\Types\BookInterface;

final class CartApi
{
	const SESSION_CART = 'abook_cart';
	/**
	 * @var array{key: string, name: string, price: float, description: string, id: int}[] SHIPPING_METHOD
	 */

	public function __construct(
		protected Session $session,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param int $bookId
	 * @param string[] $dateSelected
	 * @param int $qty
	 */
	public function addToCart(
		int $bookId,
		array $dateSelected,
		int $qty = 1,
	) {
		$cartData = $this->caculateCartData($bookId, $dateSelected, $qty);
		return $this->saveCart($cartData);
	}

	/**
	 * caculate cart data
	 * @param int $bookId
	 * @param string[] $dateSelected
	 * @param int $qty
	 * @return array
	 */
	protected function caculateCartData(
		int $bookId,
		array $dateSelected,
		int $qty = 1,
	) {
		$book = $this->getCartItemModel($bookId);
		$cartData =  [
			'status' => 'pending',
			'cart_type' => 'book',
			'cart_item' => [
				'id' => $bookId,
				'image_url' => $book->{BookInterface::IMAGE_PATH},
				'name' => $book->{BookInterface::NAME},
				'price' => $book->{BookInterface::PRICE},
				'qty' => $qty,
				'description' => $book->{BookInterface::DESCRIPTION},
				'url' => $book->url,
			],
			'currency_code' => config('ahomeglobal.currency_code'),
			'order_time' => [
				'date_from' => min($dateSelected),
				'date_to' => max($dateSelected),
				'selected_time' => $this->dateTimeHelper->getListDates($dateSelected, 'Y-m-d'),
			],
			// 'expect_order' => null,
			'total_price' => $book->{BookInterface::PRICE} * $qty,
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
		];

		$currentCart = $this->getCart();
		/**
		 * caculate book qty
		 */
		// $this->caculateQty($cartData);
		/**
		 * caculate total price
		 */
		$this->calculateTotalPrice($cartData);
		return $cartData;
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
		$this->updateCartByKey('total_price', $cart['total_price']);
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
		if (isset($cartData['cart_item'])) {
			$toTalPrice += $cartData['cart_item']['price'] * $cartData['cart_item']['qty'] * count($cartData['order_time']['selected_time']);
		}

		/**
		 * price by shipping
		 */
		if (isset($cartData['shipping_method'])) {
			$toTalPrice += $cartData['shipping_method']['shipping_cost'];
		}

		$cartData['total_price'] = $toTalPrice;
	}

	/**
	 * @param array $cartData
	 * @return void
	 */
	protected function caculateQty(&$cartData)
	{
		switch ($cartData['cart_type']) {
			case 'book':
				break;
			case 'home':
				break;
			default:
				$cartData['cart_item']['qty'] = $cartData['cart_item']['qty'];
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
		$shippingMethodList = config('abookglobal.shipping_method');
		/**
		 * find value in array: https://www.php.net/manual/en/function.array-find.php
		 */
		$shippingMethod = array_find($shippingMethodList, fn($method) => $method['key'] === $shippingMethod);
		$this->updateCartByKey('shipping_method', $shippingMethod);
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
		$this->updateCartByKey('shipping_address', $shippingAddress);
	}

	/**
	 * update payment method
	 * @param string $paymentMethod
	 * @return void
	 */
	public function updatePaymentMethod(string $paymentMethod)
	{
		$this->updateCartByKey('on_payment', $paymentMethod);
	}

	/**
	 * update cart customer info
	 * @param array $customerInfo
	 * @return void
	 */
	public function updateCustomerInfo($customerInfo)
	{
		$this->updateCartByKey('customer_info', $customerInfo);
	}

	/**
	 * @param string $expectOrder
	 * @return void
	 */
	public function updateExpectOrder(string $expectOrder)
	{
		$this->updateCartByKey('expect_order', $expectOrder);
	}

	/**
	 * chua chay thu, can kiem tra them
	 */
	public function getExpectOrder()
	{
		return $this->getCartValueByKey('expect_order');
	}

	public function clearExpectOrder()
	{
		$this->session->pull('expect_order', null);
	}

	protected function calculateExpectOrder() {}

	protected function calculateOnPaymentOrder() {}

	/*
	 * @param int $bookId
	 * @return \Thanhnt\Abookglobal\Models\Book
	 */
	protected function getCartItemModel(int $bookId)
	{
		return \Thanhnt\Abookglobal\Models\Book::findOrFail($bookId);
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
		return config('ahomeglobal.cart_key') ?? self::SESSION_CART;
	}
}
