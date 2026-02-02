<?php

namespace Thanhnt\Abookglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Abookglobal\Models\Types\BookOrderDetailInterface;

final class BookOrderDetail extends Model implements BookOrderDetailInterface
{
	use SoftDeletes;

	protected $table =  self::TABLE_NAME;
	protected $fillable = self::FILLED_FILEDS;

	protected $hidden = [];
}
