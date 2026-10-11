<?php

namespace Thanhnt\Acarglobal\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Customer extends Model implements Types\CustomerInterface
{
	use SoftDeletes;
	use Timestamp;

	/**
	 * The table associated with the model.
	 */
	protected $table = self::TABLE_NAME;

	/**
	 * The attributes that are mass assignable.
	 */
	protected $fillable = self::FILLED_FIELDS;

	/**
	 * The attributes that should be hidden for serialization.
	 */
	protected $hidden = self::HIDDEN_FIELDS;
}
