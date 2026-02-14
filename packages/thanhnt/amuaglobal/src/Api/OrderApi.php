<?php

namespace Thanhnt\Amuaglobal\Api;

use Thanhnt\Amuaglobal\Helper\ModelHelper;
use Thanhnt\Amuaglobal\Events\OrderSave;
use Thanhnt\Amuaglobal\Models\ExpectOrder;
use Thanhnt\Amuaglobal\Models\Order;
use Thanhnt\Amuaglobal\Models\Types\ExpectOrderInterface;
use Thanhnt\Amuaglobal\Models\Types\OrderInterface;

final class OrderApi
{
	public function __construct(
		protected Order $order,
		protected ExpectOrder $expectOrder,
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
	 * @return ExpectOrder
	 */
	public function createExpectOrderByCart(array $cartData)
	{
		$expectData = [
			ExpectOrderInterface::DATE_FROM => $cartData[CartApi::ORDER_TIME]['date_from'],
			ExpectOrderInterface::DATE_TO => $cartData[CartApi::ORDER_TIME]['date_to'],
			ExpectOrderInterface::SELECTED_TIME => $cartData[CartApi::ORDER_TIME]['selected_time'],
			ExpectOrderInterface::STATUS => $cartData[CartApi::STATUS] ?? 'created',
			ExpectOrderInterface::QTY => $cartData[CartApi::CART_ITEM]['qty'],
			ExpectOrderInterface::TOTAL_PRICE => $cartData[CartApi::CART_TOTAL],
			ExpectOrderInterface::CUSTOMER_INFO => $cartData[CartApi::CUSTOMER_INFO],
			ExpectOrderInterface::SHIPPING_METHOD => $cartData[CartApi::SHIPPING_METHOD]['key'],
			ExpectOrderInterface::SHIPPING_ADDRESS => $cartData[CartApi::SHIPPING_ADDRESS],
			ExpectOrderInterface::ITEM_ID => $cartData[CartApi::CART_ITEM]['id'],
			ExpectOrderInterface::PAYMENT_METHOD => $cartData[CartApi::ON_PAYMENT],
		];

		return $this->expectOrder->create($expectData);
	}

	/**
	 * Update expect order by id
	 * 
	 * @param string $id expect order id
	 * @param array $cartData cart data
	 * 
	 * @return ExpectOrder
	 */
	public function updateExpectOrder(string $id, $cartData)
	{
		$expectOrder = $this->expectOrder->findOrFail($id);
		$expectData = [
			ExpectOrderInterface::DATE_FROM => $cartData[CartApi::ORDER_TIME]['date_from'],
			ExpectOrderInterface::DATE_TO => $cartData[CartApi::ORDER_TIME]['date_to'],
			ExpectOrderInterface::SELECTED_TIME => $cartData[CartApi::ORDER_TIME]['selected_time'],
			ExpectOrderInterface::STATUS => $cartData[CartApi::STATUS] ?? 'created',
			ExpectOrderInterface::QTY => $cartData[CartApi::CART_ITEM]['qty'],
			ExpectOrderInterface::TOTAL_PRICE => $cartData[CartApi::CART_TOTAL],
			ExpectOrderInterface::CUSTOMER_INFO => $cartData[CartApi::CUSTOMER_INFO],
			ExpectOrderInterface::SHIPPING_METHOD => $cartData[CartApi::SHIPPING_METHOD]['key'],
			ExpectOrderInterface::SHIPPING_ADDRESS => $cartData[CartApi::SHIPPING_ADDRESS],
			ExpectOrderInterface::ITEM_ID => $cartData[CartApi::CART_ITEM]['id'],
			ExpectOrderInterface::PAYMENT_METHOD => $cartData[CartApi::ON_PAYMENT],
		];

		return $expectOrder->update($expectData);
	}

	/**
	 * Clone expect order to order
	 * 
	 * @param ExpectOrder $expectOrder
	 * 
	 * @return Order
	 */
	public function cloneExpectOrderToOrder(ExpectOrder $expectOrder)
	{
		$order = Order::create([
			OrderInterface::ITEM_ID => $expectOrder->{ExpectOrderInterface::ITEM_ID},
			OrderInterface::QTY => $expectOrder->{ExpectOrderInterface::QTY},
			OrderInterface::TOTAL_PRICE => $expectOrder->{ExpectOrderInterface::TOTAL_PRICE},
			OrderInterface::DATE_FROM => $expectOrder->{ExpectOrderInterface::DATE_FROM},
			OrderInterface::DATE_TO => $expectOrder->{ExpectOrderInterface::DATE_TO},
			OrderInterface::SELECTED_TIME => $expectOrder->{ExpectOrderInterface::SELECTED_TIME},
			OrderInterface::STATUS => $expectOrder->{ExpectOrderInterface::STATUS},
			OrderInterface::CUSTOMER_INFO => $expectOrder->{ExpectOrderInterface::CUSTOMER_INFO},
			OrderInterface::SHIPPING_METHOD => $expectOrder->{ExpectOrderInterface::SHIPPING_METHOD},
			OrderInterface::SHIPPING_ADDRESS => $expectOrder->{ExpectOrderInterface::SHIPPING_ADDRESS},
			OrderInterface::PAYMENT_METHOD => $expectOrder->{ExpectOrderInterface::PAYMENT_METHOD},
			OrderInterface::INCREMENT_ID => $expectOrder->{ExpectOrderInterface::ID},
		]);
		/**
		 * dispatch event after save order to sync list selected time
		 */
		OrderSave::dispatch($order);
		return $order;
	}

	/**
	 * Get order detail by increment id
	 *
	 * @param string $incrementId
	 * @return Order
	 */
	public function getOrderByIncrement(string $incrementId, $assignItemModel = null)
	{
		$order = $this->order->with('item')->where(OrderInterface::INCREMENT_ID, $incrementId)->first();
		/**
		 * assign item to order because order allways use default product for item.
		 * So we need to assign item to order to use for custom product
		 */
		if ($assignItemModel) {
			$order->item()->associate($assignItemModel);
		}
		return $order;
	}
}
