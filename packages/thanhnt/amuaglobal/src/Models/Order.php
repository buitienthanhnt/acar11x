<?php

namespace Thanhnt\Amuaglobal\Models;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Amuaglobal\Models\Types\OrderInterface;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

class Order extends Model implements OrderInterface
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

	/**
	 * liên kết tới model item(liên kết 1 - 1 nghịch đảo truyền vào class tới và khóa phụ)
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function item()
	{
		return $this->belongsTo(Page::class, self::ITEM_ID, ProductInterface::ID);
	}
}
