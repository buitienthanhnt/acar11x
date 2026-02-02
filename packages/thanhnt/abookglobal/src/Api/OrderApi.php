<?php

namespace Thanhnt\Abookglobal\Api;

use Thanhnt\Abookglobal\Models\BookExpectOrder;
use Thanhnt\Abookglobal\Models\BookOrder;
use Thanhnt\Ahomeglobal\Helper\ModelHelper;
use Thanhnt\Abookglobal\Models\Types\BookExpectOrderInterface;
use Thanhnt\Abookglobal\Models\Types\BookOrderInterface;

final class OrderApi
{
	public function __construct(
		protected BookOrder $bookOrder,
		protected BookExpectOrder $bookExpectOrder,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}


	/**
	 * @param array{status: string, cart_type: string, currency_code: string, 
	 * 	cart_item: array{id: integer, name: string, price: float, qty: integer, image_path?: string}, 
	 * 	order_time: array{date_from: string, date_to: string, selected_time: string}, 
	 * 	customer_info?: array{name: string, email: string, phone: string}, 
	 * 	shipping_method?: array{key: string, name: string, shipping_cost: float, description?: string, id?: integer}, 
	 * 	shipping_address?: array{name: string, phone: string, location: string}, 
	 * 	expect_order?: string, total_price: float, on_payment_order: array{ token?: string, id?: string,}, on_payment?: string,
	 * } $cartData
	 */
	public function createExpectOrderByCart(array $cartData)
	{
		$expectData = [
			BookExpectOrderInterface::DATE_FROM => $cartData['order_time']['date_from'],
			BookExpectOrderInterface::DATE_TO => $cartData['order_time']['date_to'],
			BookExpectOrderInterface::SELECTED_TIME => $cartData['order_time']['selected_time'],
			BookExpectOrderInterface::STATUS => 'created',
			BookExpectOrderInterface::QTY => $cartData['cart_item']['qty'],
			BookExpectOrderInterface::TOTAL_PRICE => $cartData['total_price'],
			BookExpectOrderInterface::CUSTOMER_INFO => $cartData['customer_info'],
			BookExpectOrderInterface::SHIPPING_METHOD => $cartData['shipping_method']['key'],
			BookExpectOrderInterface::SHIPPING_ADDRESS => $cartData['shipping_address'],
			BookExpectOrderInterface::BOOK_ID => $cartData['cart_item']['id'],
			BookExpectOrderInterface::PAYMENT_METHOD => $cartData['on_payment'],
		];

		return $this->bookExpectOrder->create($expectData);
	}

	/**
	 * Update expect order by id
	 * 
	 * @param string $id expect order id
	 * @param array $cartData cart data
	 * 
	 * @return BookExpectOrder
	 */
	public function updateExpectOrder(string $id, $cartData)
	{
		$bookExpectOrder = $this->bookExpectOrder->findOrFail($id);
		$expectData = [
			BookExpectOrderInterface::DATE_FROM => $cartData['order_time']['date_from'],
			BookExpectOrderInterface::DATE_TO => $cartData['order_time']['date_to'],
			BookExpectOrderInterface::SELECTED_TIME => $cartData['order_time']['selected_time'],
			BookExpectOrderInterface::STATUS => 'created',
			BookExpectOrderInterface::QTY => $cartData['cart_item']['qty'],
			BookExpectOrderInterface::TOTAL_PRICE => $cartData['total_price'],
			BookExpectOrderInterface::CUSTOMER_INFO => $cartData['customer_info'],
			BookExpectOrderInterface::SHIPPING_METHOD => $cartData['shipping_method']['key'],
			BookExpectOrderInterface::SHIPPING_ADDRESS => $cartData['shipping_address'],
			BookExpectOrderInterface::BOOK_ID => $cartData['cart_item']['id'],
			BookExpectOrderInterface::PAYMENT_METHOD => $cartData['on_payment'],
		];

		return $bookExpectOrder->update($expectData);
	}

	/**
	 * Clone expect order to order
	 * 
	 * @param BookExpectOrder $bookExpectOrder
	 * 
	 * @return BookOrder
	 */
	public function cloneExpectOrderToOrder(BookExpectOrder $bookExpectOrder)
	{
		$order = BookOrder::create([
			BookOrderInterface::BOOK_ID => $bookExpectOrder->{BookExpectOrderInterface::BOOK_ID},
			BookOrderInterface::QTY => $bookExpectOrder->{BookExpectOrderInterface::QTY},
			BookOrderInterface::TOTAL_PRICE => $bookExpectOrder->{BookExpectOrderInterface::TOTAL_PRICE},
			BookOrderInterface::DATE_FROM => $bookExpectOrder->{BookExpectOrderInterface::DATE_FROM},
			BookOrderInterface::DATE_TO => $bookExpectOrder->{BookExpectOrderInterface::DATE_TO},
			BookOrderInterface::SELECTED_TIME => $bookExpectOrder->{BookExpectOrderInterface::SELECTED_TIME},
			BookOrderInterface::STATUS => 'created',
			BookOrderInterface::CUSTOMER_INFO => $bookExpectOrder->{BookExpectOrderInterface::CUSTOMER_INFO},
			BookOrderInterface::SHIPPING_METHOD => $bookExpectOrder->{BookExpectOrderInterface::SHIPPING_METHOD},
			BookOrderInterface::SHIPPING_ADDRESS => $bookExpectOrder->{BookExpectOrderInterface::SHIPPING_ADDRESS},
			BookOrderInterface::PAYMENT_METHOD => $bookExpectOrder->{BookExpectOrderInterface::PAYMENT_METHOD},
			BookOrderInterface::INCREMENT_ID => $bookExpectOrder->{BookExpectOrderInterface::ID},
		]);
		return $order;
	}

	/**
	 * Get order detail by increment id
	 *
	 * @param string $incrementId
	 * @return BookOrder
	 */
	public function getOrderDetailByIncrement(string $incrementId)
	{
		return $this->bookOrder->with('book')->where(BookOrderInterface::INCREMENT_ID, $incrementId)->first();
	}
}
