<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface SalaryInterface
{
	const TABLE_NAME = 'acar_salaries';

	const ID = 'id';
	const NAME = 'name';
	const VALUE = 'value';

	const EMPLOYEE_ID = 'employee_id';
	const NOTE = 'note';
	
	/**
	 * per_month
	 * per_year
	 * per_hour
	 * per_day
	 * per_week
	 */
	const TYPE = 'type';

	const FILLED_FIELDS = [
		self::NAME,
		self::VALUE,
		self::TYPE,
		self::NOTE,
		self::EMPLOYEE_ID,
	];
}
