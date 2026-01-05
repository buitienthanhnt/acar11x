<?php

namespace Thanhnt\Abookglobal\Models;

use App\Models\ShareAction\ImagePathAttrModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Abookglobal\Models\Types\BookInterface;
use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Gallery;

final class Book extends Model implements BookInterface
{
	use SoftDeletes;
	use ImagePathAttrModel;

	protected $table = self::TABLE_NAME;
	protected $primaryKey = self::ID;

	protected $fillable = self::FILLED_FILEDS;

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
}
