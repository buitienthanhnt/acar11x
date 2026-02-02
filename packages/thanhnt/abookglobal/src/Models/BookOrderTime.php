<?php

namespace Thanhnt\Abookglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Abookglobal\Models\Types\BookOrderTimeInterface;

final class BookOrderTime extends Model implements BookOrderTimeInterface
{
	use SoftDeletes;

	protected $table =  self::TABLE_NAME;
	protected $fillable = self::FILLED_FILEDS;

	protected $hidden = [];

	/**
	 * khai báo chuyển đổi kiểu dữ liệu
	 */
	protected $casts = [
		self::BOOK_ID => 'array',  // Casts the 'BOOK_ID' column to an array
		self::ORDER_IDS => 'array',
	];
}
