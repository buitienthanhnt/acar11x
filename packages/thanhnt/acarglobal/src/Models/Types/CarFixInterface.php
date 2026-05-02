<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface CarFixInterface
{

	const TABLE_NAME = 'acar_car_fixs';

	const ID = 'id';
	const CAR_ID = 'car_id';
	const STATUS = 'status';

	const STATUS_PENDING = 'pending';
	const STATUS_DONE = 'done';
	const STATUS_CANCEL = 'cancel';
	const STATUS_DELETED = 'deleted';
	const STATUS_PROCESSING = 'processing';
	const STATUS_WAIT = 'wait';

	const CUSTOMER = 'customer';
	const PHONE = 'phone';
	const ADDRESS = 'address';
	const VAT = 'vat';
	const KM = 'km';

	const FILLED_FIELDS = [
		self::STATUS,
		self::CAR_ID,
		self::CUSTOMER,
		self::PHONE,
		self::ADDRESS,
		self::KM,
	];
}
