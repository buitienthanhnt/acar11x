<?php

namespace Thanhnt\Amuaglobal\Api;

use Illuminate\Http\Request;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

final class CategoryApi
{
	function __construct(
		protected Request $request,
		protected Category $category,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function allCategory()
	{
		return $this->category->all();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function categoryTree()
	{
		return $this->category->where(CategoryInterface::PARENT_ID, 0)->with(CategoryInterface::R_CHILDRENS)->get();
	}
}
