<?php

namespace Thanhnt\Amuaglobal\Models\Repository;

use Illuminate\Http\Request;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

final class CategoryRepository
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

	/**
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function categoryDetail(int $id)
	{
		return $this->category->with([CategoryInterface::R_CHILDREN, CategoryInterface::R_PARENT])->find($id);
	}
}
