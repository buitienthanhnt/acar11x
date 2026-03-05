<?php

namespace Thanhnt\Amuaglobal\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thanhnt\Amuaglobal\Database\Factories\GalleryFactory;
use Thanhnt\Amuaglobal\Models\Types\GalleryInterface;

#[UseFactory(GalleryFactory::class)]
class Gallery  extends Model implements GalleryInterface
{
	use HasFactory;

	/**
	 * define for fillable field
	 */
	protected $fillable = self::FILLED_FILEDS;

    protected $table = self::TABLE_NAME;

	/**
	 * define for hidden field
	 */
	protected $hidden = self::HIDDEN_FIELDS;

	public $timestamps = false;

	public function path(): Attribute
	{
		return Attribute::make(
			// get: fn($value) => asset($value),
			set: fn($value) => parse_url($value)['path'],
		);
	}
}
