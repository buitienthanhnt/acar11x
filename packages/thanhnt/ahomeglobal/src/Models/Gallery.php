<?php

namespace Thanhnt\Ahomeglobal\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Thanhnt\Ahomeglobal\Models\Types\GalleryInterface;

class Gallery  extends Model implements GalleryInterface
{
	/**
	 * define for fillable field
	 */
	protected $fillable = self::FILLED_FILEDS;

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
