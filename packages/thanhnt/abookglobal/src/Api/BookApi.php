<?php

namespace Thanhnt\Abookglobal\Api;

use Thanhnt\Abookglobal\Models\Book;
use Thanhnt\Abookglobal\Models\BookOrderTime;

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
		$book = Book::findOrFail($bookId);
		return $book;
	}

	public function getBookOrderTimes(int $bookId)
	{
		$book = Book::findOrFail($bookId);
		$times = BookOrderTime::whereTodayOrAfter(BookOrderTime::DATE)
			->whereJsonContains(BookOrderTime::BOOK_ID, $book->id)
			->get();
	}
}
