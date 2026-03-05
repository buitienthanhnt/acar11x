<?php

namespace Thanhnt\Abookglobal\Models\Repository;

use Thanhnt\Abookglobal\Models\Book;

final class BookRepository
{
	public function __construct(
		protected Book $book,
	) {
		// throw new \Exception('Not implemented');
	}
	public function getBookDetail(int $id)
	{
		return $this->book->with(['bookCate', 'attr', 'gallery', 'bookOrders'])->find($id);
	}

	/**
	 * Get book detail by alias
	 *
	 * @param string $alias
	 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getBookDetailByAlias(string $alias)
	{
		return $this->book->with(['bookCate', 'attr', 'gallery', 'bookOrders'])->where('alias', $alias)->first();
	}
}
