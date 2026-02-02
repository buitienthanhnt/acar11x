<?php

namespace Thanhnt\Abookglobal\Models\Types;


interface BookOrderDetailInterface
{
	const TABLE_NAME = 'abook_book_order_details';

	const ORDER_ID = 'order_id';
	const NAME = 'name';
	const PHONE = 'phone';
	const EMAIL = 'email';
	// const SHIPPING = 'shipping';
	const PAYMENT_METHOD = 'payment_method'; // paypal, stripe, await
	const PRICE = 'price';
	const QUANTITY = 'quantity';
	const TOTAL_PRICE = 'total_price';
	const CURRENCY = 'currency';

	const FILLED_FILEDS = [self::ORDER_ID, self::NAME, self::PHONE, self::EMAIL, self::PAYMENT_METHOD, self::PRICE, self::QUANTITY, self::TOTAL_PRICE, self::CURRENCY];
}
