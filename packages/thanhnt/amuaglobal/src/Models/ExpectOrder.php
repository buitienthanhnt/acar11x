<?php

namespace Thanhnt\Amuaglobal\Models;

use App\Models\Page;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Models\Types\ExpectOrderInterface;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

class ExpectOrder extends Model implements ExpectOrderInterface
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

	/**
	 * liên kết tới model item(liên kết 1 - 1 nghịch đảo truyền vào class tới và khóa phụ)
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function item()
	{
		return $this->belongsTo(Page::class, self::ITEM_ID, ProductInterface::ID);
	}
}
