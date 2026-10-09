<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;
use Thanhnt\Acarglobal\Models\Types\CustomerInterface;

class Car extends Model implements CarInterface
{
	use SoftDeletes;

	protected $table = self::TABLE_NAME;

	protected $fillable = self::FILLED_FIELDS;
	protected $hidden = self::HIDDEN_FIELDS;

	/**
	 * link to car fix(has many car fix)
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function carFix(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(CarFix::class, CarFixInterface::CAR_ID, CarInterface::ID);
	}

	/**
	 * link to customer(belongs to customer)
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Customer::class, CarInterface::CUSTOMER_ID, CustomerInterface::ID);
	}
}
