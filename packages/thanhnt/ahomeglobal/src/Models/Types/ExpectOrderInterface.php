<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface ExpectOrderInterface
{
	const TABLE_NAME = 'expect_orders';

	const ID = 'id';
	const ROOM_ID = 'room_id';
	const HOME_ID = 'home_id';
	const DATE_FROM = 'date_from';
	const DATE_TO = 'date_to';
	const SELECTED_TIME = 'selected_time';
	const QTY = 'qty';
	const TOTAL_PRICE = 'total_price';
	const STATUS = 'status'; // complete, success, cancel,

	const HIDDEN_FIELDS = [self::HOME_ID, self::ROOM_ID,];

	/**
	 * define fields can be filled
	 */
	const FILLED_FILEDS = [self::ROOM_ID, self::HOME_ID, self::DATE_FROM, self::DATE_TO, self::SELECTED_TIME, self::QTY, self::TOTAL_PRICE, self::STATUS,];
}
