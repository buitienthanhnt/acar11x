<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Acarglobal\Models\Types\{ActivityInterface, CarFixInterface, CarInterface};
use Thanhnt\Amuaglobal\Models\Product;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

final class Activity extends Model implements ActivityInterface
{

	protected $table = self::TABLE_NAME;
	protected $fillable = self::FILLED_FIELDS;
	protected $hidden = self::HIDDEN_FIELDS;

	/**
	 * link to car info
	 */
	public function car()
	{
		return $this->belongsTo(Car::class, self::CAR_ID, CarInterface::ID);
	}

	/**
	 * link to car fix
	 */
	public function carFix()
	{
		return $this->belongsTo(CarFix::class, self::CAR_FIX_ID, CarFixInterface::ID);
	}

	/**
	 * link to product
	 */
	public function product()
	{
		return $this->belongsTo(Product::class, self::PRODUCT_ID, ProductInterface::ID);
	}
}
