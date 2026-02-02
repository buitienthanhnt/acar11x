<?php

namespace Thanhnt\Abookglobal\Models\Types;

interface BookOrderInterface
{
	const TABLE_NAME = 'abook_orders';

	const ID = 'id';
	const BOOK_ID = 'book_id';
	const DATE_FROM = 'date_from';
	const DATE_TO = 'date_to';
	const SELECTED_TIME = 'selected_time';
	const QTY = 'qty';
	const TOTAL_PRICE = 'total_price';
	const STATUS = 'status'; // complete, success, cancel,
	const INCREMENT_ID = 'increment_id';
	const PAYMENT_METHOD = 'payment_method';

	const SHIPPING_METHOD = 'shipping_method'; // string
	const SHIPPING_ADDRESS = 'shipping_address';
	const CUSTOMER_INFO = 'customer_info';

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', self::BOOK_ID,];

	const FILLED_FILEDS = [
		self::BOOK_ID,
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
