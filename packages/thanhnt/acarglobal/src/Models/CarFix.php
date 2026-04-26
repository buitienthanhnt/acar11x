<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;

final class CarFix extends Model implements CarFixInterface
{
	use SoftDeletes;

	protected $table = self::TABLE_NAME;
	protected $fillable = self::FILLED_FIELDS;
	protected $appends = ['status_label'];

	protected function statusLabel(): Attribute
	{
		return Attribute::make(
			get: function () {
				switch ($this->{self::STATUS}) {
					case 'wait':
						return 'đang chờ báo giá';
						break;
					case 'processing':
						return 'đang thực hiện';
					case 'done':
						return 'Hoàn thành';
						break;
					default:
						# code...
						break;
				}
			}
		);
	}
	/**
	 * link to car info
	 */
	public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Car::class, self::CAR_ID, CarInterface::ID);
	}

	/**
	 * link to activity
	 */
	public function activities()
	{
		return $this->hasMany(Activity::class, ActivityInterface::CAR_FIX_ID, CarFixInterface::ID);
	}
}
