<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Models\Casts\ImageAttrModel;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

class Product extends Model implements ProductInterface
{
	use ImageAttrModel;

	/**
	 * define table name
	 */
	protected $table = self::TABLE_NAME;

	/**
	 * define mass assignable(gán hàng loạt)
	 */
	protected $fillable = self::FILLED_FILEDS;
}
