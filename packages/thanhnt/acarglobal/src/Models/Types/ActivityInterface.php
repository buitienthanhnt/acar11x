<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface ActivityInterface
{
	const TABLE_NAME = 'acar_activities';

	const ID = 'id';
	const CAR_ID = 'car_id';
	const CAR_FIX_ID = 'car_fix_id';
	
	const STATUS = 'status';
	const STATUS_ACTIVE = 'active';
	const STATUS_INACTIVE = 'inactive';
	const STATUS_DONE = 'done';
	const STATUS_CANCEL = 'cancel';
	const STATUS_WAIT = 'wait';
	const STATUS_PROCESSING = 'processing';
	const STATUS_COMPLETE = 'complete';
	const STATUS_FAIL = 'fail';
	const STATUS_REJECT = 'reject';
	const STATUS_EXPIRED = 'expired';
	const STATUS_NEW = 'new';

	const TITLE = 'title';
	const NOTE = 'note';
	const QTY = 'qty';
	const PRICE = 'price';
	const PRODUCT_ID = 'product_id';
	/**
	 * vi du:
	 * dau may | 10 | 80 | 
	 */

	const FILLED_FIELDS = [
		self::CAR_ID,
		self::CAR_FIX_ID,
		self::STATUS,
		self::NOTE,
		self::TITLE,
		self::QTY,
		self::PRICE,
		self::PRODUCT_ID,
	];

	const HIDDEN_FIELDS = ['created_at', 'updated_at', 'deleted_at'];

	const FORM_FIELDS = [
		[
			'name' => self::TITLE,
			'type' => 'text',
			'value' => '',
			'label' => 'Tieu de',
		],
		// [
		// 	'name' => self::NOTE,
		// 	'type' => 'text',
		// 	'value' => '',
		// 	'label' => 'Ghi chu',
		// ],
		[
			'name' => self::STATUS,
			'type' => 'select',
			'value' => '',
			'label' => 'Trang thai',
			'options' => [
				['value' => 0, 'label' => 'Chua hoan thanh'],
				['value' => 1, 'label' => 'Hoan thanh'],
			]
		],
		[
			'name' => self::QTY,
			'type' => 'number',
			'value' => '',
			'label' => 'So luong',
		],
		[
			'name' => self::PRICE,
			'type' => 'number',
			'value' => '',
			'label' => 'Gia',
		],
		[
			'name' => self::CAR_FIX_ID,
			'type' => 'number',
			'value' => '',
			'label' => 'id ho so',
			'options' => [],
		],
		[
			'name' => self::CAR_ID,
			'type' => 'number',
			'value' => '',
			'label' => 'id ho so',
		]
	];
}
