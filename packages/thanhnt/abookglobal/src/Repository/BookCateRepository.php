<?php

namespace Thanhnt\Abookglobal\Repository;

use Thanhnt\Abookglobal\Models\BookCate;
use Thanhnt\Abookglobal\Models\Types\BookCateInterface;
use Thanhnt\Amuaglobal\Helper\ModelHelper;

final class BookCateRepository
{
	public function __construct(
		protected BookCate $bookCate,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}
	public function register(array $data = [])
	{
		$this->bookCate->create($this->modelHelper->massDataAttribute(BookCateInterface::FILLED_FILEDS, $data));
	}
}
