<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Amuaglobal\Models\Types\OrderTimeInterface;

final class OrderTime extends Model implements OrderTimeInterface
{
	protected $table =  self::TABLE_NAME;
	protected $fillable = self::FILLED_FILEDS;
	public $timestamps = false;

	/**
	 * khai báo chuyển đổi kiểu dữ liệu
	 */
	protected $casts = [
		self::ITEM_ID => 'array',  // Casts the 'ITEM_ID' column to an array
		self::ORDER_IDS => 'array',
	];
}
