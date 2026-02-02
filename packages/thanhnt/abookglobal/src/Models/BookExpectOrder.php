<?php
namespace Thanhnt\Abookglobal\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Thanhnt\Abookglobal\Models\Types\BookExpectOrderInterface;

final class BookExpectOrder extends Model implements BookExpectOrderInterface
{
	use HasUlids; // Use trait

	protected $table = self::TABLE_NAME;
	protected $fillable = self::FILLED_FIELDS;
	public $timestamps = false;

	protected $casts = [
		self::CUSTOMER_INFO => 'array', // Casts the 'SELECTED_TIME' column to an array
		self::SELECTED_TIME => 'array',
		self::SHIPPING_ADDRESS => 'array',
	];
	
}
