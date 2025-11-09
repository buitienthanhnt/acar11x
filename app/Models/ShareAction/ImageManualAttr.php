<?php

namespace App\Models\ShareAction;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ImageManualAttr
{
	/**
	 * định dạng giá trị thuộc tính trước khi trả về.
	 * nó giống plugin trong m2.
	 * Lưu ý chuyển tên hàm sang dạng CamelKey 
	 * @return Attribute
	 */
	function imagePath(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				return asset($value);
			},
		);
	}
}
