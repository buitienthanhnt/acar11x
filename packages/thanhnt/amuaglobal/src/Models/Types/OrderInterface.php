<?php

namespace Thanhnt\Amuaglobal\Models\Types;

interface OrderInterface extends ExpectOrderInterface
{
	const TABLE_NAME = 'amua_orders';

	const INCREMENT_ID = 'increment_id';

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', self::ITEM_ID,];

	const FILLED_FILEDS = [
		self::ITEM_ID,
		self::DATE_FROM,
		self::DATE_TO,
		self::SELECTED_TIME,
		self::QTY,
		self::TOTAL_PRICE,
		self::STATUS,
		self::INCREMENT_ID,
		self::PAYMENT_METHOD,
		self::SHIPPING_METHOD,
		self::SHIPPING_ADDRESS,
		self::CUSTOMER_INFO,

	];
}
