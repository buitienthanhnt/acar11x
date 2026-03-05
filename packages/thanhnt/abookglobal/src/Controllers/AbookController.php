<?php

namespace Thanhnt\Abookglobal\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Thanhnt\Abookglobal\Api\BookApi;
use Thanhnt\Abookglobal\Api\BookCategoryApi;
use Thanhnt\Abookglobal\Models\Book;
use Thanhnt\Abookglobal\Models\Repository\BookRepository;

final class AbookController extends Controller
{

	public function __construct(
		protected BookRepository $bookRepository,
		protected BookApi $bookApi,
		protected BookCategoryApi $bookCategoryApi,
	) {
		// throw new \Exception('Not implemented');
	}
	public function index()
	{
		return Inertia::render('Abookglobal/Screens/HomePage', [
			'books' => fn() => Book::paginate(12),
		]);
	}

	/**
	 * @param string $alias
	 * @return \Inertia\Response
	 */
	public function bookDetail(string $alias)
	{
		// swipe image: https://react-slick.neostack.com/docs/example/custom-paging
		$book = $this->bookRepository->getBookDetailByAlias($alias);
		if (!$book) {
			return redirect()->back()->with('error', 'Không tìm thấy sách');
		}
		/**
		 * get booked times
		 */
		$bookedOrder = $book->bookOrders()->getQuery()->select(['selected_time', 'qty'])->get()->toArray();
		$timeOrderBooked = [];
		foreach ($bookedOrder as $order) {
			foreach ($order['selected_time'] as $date) {
				if (isset($timeOrderBooked[$date])) {
					$timeOrderBooked[$date] += $order['qty'];
				} else {
					$timeOrderBooked[$date] = $order['qty'];
				}
			}
		}

		return Inertia::render('Abookglobal/Screens/BookDetail', [
			'book' => $book,
			'bookedTimes' => $timeOrderBooked,
			'relatedBooks' => Book::inRandomOrder()->take(4)->get(),
			// 'relatedBooks' => $this->bookApi->getRelatedBooks($book->{Book::ID}, $book->{Book::BOOK_CATE_ID}),
		]);
	}

	public function bookByCategory(string $alias)
	{
		$bookCategory =  $this->bookCategoryApi->getBookCategoryById($alias);
		// $books = $this->bookApi->getBooksByCategoryAlias($alias);

		return Inertia::render('Abookglobal/Screens/BookByCategory', [
			'category' => $bookCategory,
			'bookPaginate' => $bookCategory->books()->paginate(12),
		]);
	}
}
