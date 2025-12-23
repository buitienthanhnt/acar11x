<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface OrderInterface
{
	const TABLE_NAME = 'orders';

	const ID = 'id';
	const ROOM_ID = 'room_id';
	const HOME_ID = 'home_id';
	const DATE_FROM = 'date_from';
	const DATE_TO = 'date_to';
	const SELECTED_TIME = 'selected_time';
	const QTY = 'qty';
	const TOTAL_PRICE = 'total_price';
	const STATUS = 'status'; // complete, success, cancel,

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', self::HOME_ID, self::ROOM_ID,];

	const FILLED_FILEDS = [self::ROOM_ID, self::HOME_ID, self::DATE_FROM, self::DATE_TO, self::SELECTED_TIME, self::QTY, self::TOTAL_PRICE, self::QTY, self::STATUS,];

	/**
	 * define relation names
	 * value must be same function name in model
	 */
	const ROOM = 'room'; // relation order belong to room
	const HOME = 'home'; // relation order belong to home
}
