<?php

namespace Thanhnt\Abookglobal\Api;

use Thanhnt\Abookglobal\Models\Book;

final class BookApi
{
	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param int $bookId
	 * @return \Thanhnt\Abookglobal\Models\Book
	 */
	public function getBookById(int $bookId)
	{
		return Book::findOrFail($bookId);
	}
}
