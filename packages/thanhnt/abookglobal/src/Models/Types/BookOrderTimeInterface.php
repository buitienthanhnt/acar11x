<?php

namespace Thanhnt\Abookglobal\Models\Types;


interface BookOrderTimeInterface {
	const TABLE_NAME = 'abook_order_times';

	const ID = 'id';
	const BOOK_ID = 'book_id';
	const ORDER_IDS = 'order_ids';
	const DATE = 'date';

	/**
	 * define fillable fields for model(mass assignment)
	 */
	const FILLED_FILEDS = [self::BOOK_ID, self::ORDER_IDS, self::DATE];

	/**
	 * define list of hidden fields
	 */
	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at',];
}
