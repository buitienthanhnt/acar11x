<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;

final class CarFix extends Model implements CarFixInterface
{
	use SoftDeletes;

	protected $table = self::TABLE_NAME;
	protected $fillable = self::FILLED_FIELDS;

	/**
	 * link to car info
	 */
	public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Car::class, self::CAR_ID, CarInterface::ID);
	}
}
