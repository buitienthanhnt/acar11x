<?php

namespace Thanhnt\Amuaglobal\Models\Types;


interface OrderTimeInterface
{
	const TABLE_NAME = 'amua_order_times';

	const ID = 'id';
	const ITEM_ID = 'item_id';
	const ORDER_IDS = 'order_ids';
	const DATE = 'date';

	/**
	 * define fillable fields for model(mass assignment)
	 */
	const FILLED_FILEDS = [self::ITEM_ID, self::ORDER_IDS, self::DATE];
}
