<?php

namespace Thanhnt\Acarglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Acarglobal\Models\Types\AcarConfigInterface;

final class AcarConfig extends Model implements AcarConfigInterface {
	
	protected $table = self::TABLE_NAME;
	protected $fillable = self::FILLED_FIELDS;

	public $timestamps = false;
}
