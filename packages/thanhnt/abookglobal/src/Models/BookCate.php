<?php

namespace Thanhnt\Abookglobal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Abookglobal\Models\Types\BookCateInterface;
use Thanhnt\Abookglobal\Models\Types\BookInterface;

final class BookCate extends Model implements BookCateInterface
{
	use SoftDeletes;

	protected $table = self::TABLE_NAME;
	protected $primaryKey = self::ID;

	protected $fillable = self::FILLED_FILEDS;

	/**
	 * 
	 */
	public static function bookCateOptions()
	{
		return array_map(function ($item) {
			return [
				'value' => $item[self::ID],
				'label' => $item[self::NAME],
			];
		}, BookCate::all()->toArray());
	}

	/**
	 * link to books
	 * links many to many
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function books()
	{
		return $this->belongsToMany(Book::class, BookInterface::LINK_CATEGORY_TABLE, 'book_cate_id', 'book_id');
	}
}
