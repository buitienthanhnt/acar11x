<?php

namespace App\Models\ShareAction;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ActiveAttrModel
{
	/**
	 * format data of model after get or set action
	 * https://laravel.com/docs/12.x/eloquent-mutators#defining-an-accessor
	 * https://laravel.com/docs/12.x/eloquent-mutators#defining-a-mutator
	 */
	function active(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				return $value;
				return $value ? __('attrval.active') : __('attrval.inactive');
			},
			set: fn(mixed $value) => !!$value,
		);
	}
}
