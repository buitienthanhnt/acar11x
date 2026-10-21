<?php

namespace Thanhnt\Amuaglobal\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Override;

class ActiveScope implements Scope
{

	#[Override]
	/**
	 * Apply the scope to a given Eloquent query builder.
	 * 
	 */
	public function apply(Builder $builder, Model $model): void
	{
		/**
		 * get path of request
		 * không dùng request trong construct function vì
		 * Trong Laravel, 
		 * Global Scope không tự động nhận Dependency Injection qua Constructor giống như Controller hay Middleware.
		 */
		$path = request()->path();
		strpos($path, 'adminhtml') !== 0 && $builder->where('active', '=', true);
		// $builder->where('active', '=', true);
	}
}
