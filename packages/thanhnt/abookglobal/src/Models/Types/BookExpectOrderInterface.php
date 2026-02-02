<?php

namespace Thanhnt\Abookglobal\Models\Types;

interface BookExpectOrderInterface
{
	const TABLE_NAME = 'abook_expect_orders';

	const ID = 'id';
	const DATE_FROM = 'date_from';
	const DATE_TO = 'date_to';
	const SELECTED_TIME = 'selected_time';
	const QTY = 'qty';
	const TOTAL_PRICE = 'total_price';
	const STATUS = 'status'; // complete, success, cancel,
	const PAYMENT_METHOD = 'payment_method';

	const SHIPPING_METHOD = 'shipping_method'; // string
	const SHIPPING_ADDRESS = 'shipping_address';
	const CUSTOMER_INFO = 'customer_info';
	const BOOK_ID = 'book_id';

	const FILLED_FIELDS = [
		self::DATE_FROM,
		self::DATE_TO,
		self::SELECTED_TIME,
		self::QTY,
		self::TOTAL_PRICE,
		self::STATUS,
		self::PAYMENT_METHOD,
		self::SHIPPING_METHOD,
		self::SHIPPING_ADDRESS,
		self::CUSTOMER_INFO,
		self::BOOK_ID,
	];
}
