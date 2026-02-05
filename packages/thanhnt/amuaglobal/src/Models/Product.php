<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

class Product extends Model implements ProductInterface
{
	protected $table = self::TABLE_NAME;

	protected $fillable = self::FILLED_FILEDS;
}
