<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface EmployeeInerface
{
	const TABLE_NAME = 'acar_employees';

	const ID = 'id';
	const NAME = 'name';
	const EMAIL = 'email';
	const PHONE = 'phone';
	const STATUS = 'status';
	const ADDRESS = 'address';
	const DATE_SALARY = 'date_s';

	const STATUS_MASTER = 'height';
	const STATUS_MEDIUM = 'medium';
	const STATUS_LOW = 'low';
	// const DATE_SALARY = 'date_s';

	const FILLED_FIELDS = [
		self::NAME,
		self::EMAIL,
		self::PHONE,
		self::STATUS,
		self::ADDRESS,
		self::DATE_SALARY,
	];

	const FORM_FIELDS = [
		[
			'name' => self::NAME,
			'type' => 'text',
			'label' => 'Name',
		],
		[
			'name' => self::EMAIL,
			'type' => 'text',
			'label' => 'Email',
		],
		[
			'name' => self::PHONE,
			'type' => 'text',
			'label' => 'Phone',
		],
		[
			'name' => self::STATUS,
			'type' => 'text',
			'label' => 'Status',
		],
		[
			'name' => self::ADDRESS,
			'type' => 'text',
			'label' => 'Address',
		],
		[
			'name' => self::DATE_SALARY,
			'type' => 'text',
			'label' => 'Date Salary',
		]
	];
}
