<?php

namespace Thanhnt\Abookglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Abookglobal\Models\Types\BookInterface;
use Thanhnt\Abookglobal\Models\Types\BookOrderInterface;

final class BookOrder extends Model implements BookOrderInterface
{
	use SoftDeletes;

	protected $table =  self::TABLE_NAME;
	protected $fillable = self::FILLED_FILEDS;

	protected $hidden = [];

	/**
	 * khai báo chuyển đổi kiểu dữ liệu
	 */
	protected $casts = [
		self::SELECTED_TIME => 'array',  // Casts the 'SELECTED_TIME' column to an array
		self::CUSTOMER_INFO => 'array',
		self::SHIPPING_ADDRESS => 'array',
	];

	public function book()
	{
		return $this->belongsTo(Book::class, self::BOOK_ID, BookInterface::ID);
	}
}
