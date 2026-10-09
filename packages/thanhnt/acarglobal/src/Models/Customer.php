<?php

namespace Thanhnt\Acarglobal\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Customer extends Model implements Types\CustomerInterface
{
	use SoftDeletes;
	use Timestamp;
	
	protected $table = self::TABLE_NAME;

	protected $fillable = self::FILLED_FIELDS;
}
