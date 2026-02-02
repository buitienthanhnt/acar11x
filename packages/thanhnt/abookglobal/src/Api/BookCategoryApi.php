<?php

namespace Thanhnt\Abookglobal\Api;

use Thanhnt\Abookglobal\Models\BookCate;

final class BookCategoryApi
{
	public function __construct(
		protected BookCate $bookCategoryModel,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * Get book category by ID
	 * @param int $id
	 * @return BookCate
	 */
	public function getBookCategoryById(int $id)
	{
		return $this->bookCategoryModel->findOrFail($id);
	}
}
