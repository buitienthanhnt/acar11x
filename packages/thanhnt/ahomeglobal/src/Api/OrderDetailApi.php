<?php

namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Acarglobal\Models\OrderDetail;
use Thanhnt\Ahomeglobal\Models\Types\OrderDetailInterface;

final class OrderDetailApi
{
	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param int $orderId
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, expect_order: string}|null $cartParams
	 */
	public function saveOrderDetail($orderId, array $data)
	{
		$orderDetailData = $this->formatCartToOrderDetail($data);
		return OrderDetail::create([
			OrderDetailInterface::ORDER_ID => $orderId,
			...$orderDetailData,
		]);
	}

	/**
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, expect_order: string}|null $cartParams
	 */
	public function formatCartToOrderDetail($cartParams)
	{
		return [
			OrderDetailInterface::EMAIL => $cartParams['customer_info']['email'],
			OrderDetailInterface::PHONE => $cartParams['customer_info']['phone'],
			OrderDetailInterface::NAME => $cartParams['customer_info']['name'], // info
			OrderDetailInterface::CURRENCY => $cartParams['currency_code'],
			OrderDetailInterface::TOTAL_PRICE => $cartParams['total_price'],
			OrderDetailInterface::QUANTITY => $cartParams['qty'] ?? 1,
			OrderDetailInterface::PRICE => $cartParams['item']['price'],
			OrderDetailInterface::PAYMENT_METHOD => $cartParams['on_payment'],
		];
	}
}
