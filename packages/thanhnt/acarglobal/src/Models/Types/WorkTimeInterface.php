<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface WorkTimeInterface
{
	const TABLE_NAME = 'acar_work_times';

	/**
	 * ngay lam viec
	 */
	const DATE = 'date';

	/**
	 * cong lam viec vd: 0.5 cong, 1 cong, 1.5 cong, 2 cong
	 */
	const TIME_WORK = 'time_work';
	const DESCRIPTION = 'description';

	const FILLED_FIELDS = [
		self::DATE,
		self::TIME_WORK,
		self::DESCRIPTION,
	];

	const FORM_FIELDS = [
		[
			'name' => self::DATE,
			'type' => 'date',
			'value' => null,
		],
		[
			'name' => self::TIME_WORK,
			'type' => 'array',
			'value' => null,
		],
		[
			'name' => self::DESCRIPTION,
			'type' => 'text',
			'value' => null,
		],
	];
}
