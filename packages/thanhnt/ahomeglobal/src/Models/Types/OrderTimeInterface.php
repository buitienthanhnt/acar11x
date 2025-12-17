<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface OrderTimeInterface
{
	// table name
	const TABLE_NAME = 'order_times';

	/**
	 * define main attributes
	 */
	const ID = 'id';
	const HOME_ID = 'home_id';
	const ROOM_IDS = 'room_ids';
	const ORDER_IDS = 'order_ids';
	const DATE = 'date';

	/**
	 * define fillable fields for model(mass assignment)
	 */
	const FILLED_FILEDS = [self::HOME_ID, self::ORDER_IDS, self::ROOM_IDS, self::DATE];

	/**
	 * define list of hidden fields
	 */
	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at', 'order_ids'];
}
