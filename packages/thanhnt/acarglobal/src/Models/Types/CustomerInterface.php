<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface CustomerInterface
{
	const TABLE_NAME = 'acar_customers';

	const ID = 'id';
	const NAME = 'name';
	const PHONE = 'phone';
	const ADDRESS = 'address';
	const COMPANY = 'company';

	const FILLED_FIELDS = [
		self::NAME,
		self::PHONE,
		self::ADDRESS,
		self::COMPANY,
	];
}
