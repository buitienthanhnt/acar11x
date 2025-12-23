<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thanhnt\Ahomeglobal\Database\Factories\ExpectOrderFactory;
use Thanhnt\Ahomeglobal\Models\Types\ExpectOrderInterface;

/**
 * expert same as order, it different id from order 
 */
#[UseFactory(ExpectOrderFactory::class)]
final class ExpectOrder extends Model implements ExpectOrderInterface
{
	use HasUlids; // Use trait
	use HasFactory;

	/**
	 * khai báo chuyển đổi kiểu dữ liệu
	 */
	protected $casts = [
		self::SELECTED_TIME => 'array',  // Casts the 'SELECTED_TIME' column to an array
	];

	protected $fillable = self::FILLED_FILEDS;
}
