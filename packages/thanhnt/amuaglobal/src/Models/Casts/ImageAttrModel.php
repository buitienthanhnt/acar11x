<?php

namespace Thanhnt\Amuaglobal\Models\Casts;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

trait ImageAttrModel
{
	/**
	 * format image to storage path in model
	 * @return Attribute
	 */
	public function image(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				/**
				 * trả về đường dẫn tương đối của file trong thư mục storage 
				 * example: /storage/categories/image4.png
				 */
				return $value ? Storage::url($value) : null;
			}
		);
	}
}
