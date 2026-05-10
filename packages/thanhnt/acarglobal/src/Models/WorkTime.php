<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Acarglobal\Models\Types\WorkTimeInterface;

final class WorkTime extends Model implements WorkTimeInterface
{
	protected $table = self::TABLE_NAME;

	protected $fillable = self::FILLED_FIELDS;

	protected $casts = [
		self::TIME_WORK => 'array',
	];
}
