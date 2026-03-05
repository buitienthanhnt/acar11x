<?php

namespace Thanhnt\Abookglobal\Models;

use App\Models\ShareAction\AliasAttrModel;
use App\Models\ShareAction\ImagePathAttrModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Thanhnt\Abookglobal\Models\Types\BookInterface;
use Thanhnt\Amuaglobal\Helper\StringHelper;
use Thanhnt\Amuaglobal\Models\Attr;
use Thanhnt\Amuaglobal\Models\Gallery;
use Thanhnt\Amuaglobal\Models\Order;

final class Book extends Model implements BookInterface
{
	use SoftDeletes;
	use ImagePathAttrModel;
	use AliasAttrModel;

	protected $table = self::TABLE_NAME;
	protected $primaryKey = self::ID;

	protected $fillable = self::FILLED_FILEDS;

	protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = ['url'];

	public function attr()
	{
		return $this->hasMany(Attr::class, Attr::SOURCE_ID, self::ID)->where(Attr::TYPE, 'book');
	}

	/**
	 * link to list gallery of the book
	 */
	public function gallery(): HasMany
	{
		return $this->hasMany(Gallery::class, Gallery::SOURCE_ID, self::ID)->where(Gallery::TYPE, 'book');
	}

	public function bookCate(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(BookCate::class, 'book_cate_link', 'book_id', 'book_cate_id');
	}

	public function bookOrders(): HasMany
	{
		return $this->hasMany(Order::class, Order::ITEM_ID, self::ID)->whereTodayOrAfter(Order::DATE_TO);
	}

	protected function url(): Attribute
	{
		return new Attribute(
			get: fn() => route('abook.detail', ['alias' => $this->{self::ALIAS} ?: Str::slug(StringHelper::vn_to_str($this->{self::NAME}, true))]),
		);
	}
}
