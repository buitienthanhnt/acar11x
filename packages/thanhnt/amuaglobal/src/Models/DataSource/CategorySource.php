<?php

namespace Thanhnt\Amuaglobal\Models\DataSource;

use Override;
use Thanhnt\Amuaglobal\Models\Category;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

class CategorySource extends AbstractSource
{
	public function __construct(
		protected Category $category,
	) {}

	#[Override]
	/**
	 * @return array{value: string, label: string}
	 */
	public function sourceModel()
	{
		return $this->categoriesSource(prefix: '_');
	}

	/**
	 * @return array{value: string, label: string}
	 */
	protected function categoriesSource($parentId = 0, $prefix = '__')
	{
		$response = [];
		/**
		 * load categories by parent id
		 */
		$categories = $this->category->where(CategoryInterface::PARENT_ID, $parentId)->with(CategoryInterface::R_CHILDREN)->get();
		/**
		 * loop categories and push into response
		 */
		foreach ($categories as $category) {
			$response[] = [
				'value' => $category->id,
				'label' => $prefix . ' ' . $category->name,
			];
			/**
			 * check if category has children
			 * then call this function recursively
			 */
			if ($category->children->count()) {
				$response = array_merge($response, $this->categoriesSource($category->id, $prefix . $prefix));
			}
		}
		return $response;
	}
}
