<?php

namespace Thanhnt\Abookglobal\Models\Repository;

use Thanhnt\Abookglobal\Models\Book;

final class BookRepository
{
	public function __construct(
		protected Book $book,
	)
	{
		// throw new \Exception('Not implemented');
	}
	public function getBookDetail(int $id) {
		return $this->book->with(['bookCate', 'attr', 'gallery', 'bookOrders'])->find($id);
	}
}
