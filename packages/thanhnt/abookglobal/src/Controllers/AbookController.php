<?php

namespace Thanhnt\Abookglobal\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Thanhnt\Abookglobal\Models\Book;
use Thanhnt\Abookglobal\Models\Repository\BookRepository;

final class AbookController extends Controller
{

	public function __construct(
		protected BookRepository $bookRepository,
	)
	{
		// throw new \Exception('Not implemented');
	}
	public function index()
	{
		$books = Book::paginate(12);
		// dd($books);
		return Inertia::render('Abookglobal/Screens/HomePage', [
			'books' => $books
		]);
	}

	public function bookDetail(int $id)
	{
		// swipe image: https://react-slick.neostack.com/docs/example/custom-paging
		$book = $this->bookRepository->getBookDetail($id);
		// dd($book->toArray());

		return Inertia::render('Abookglobal/Screens/BookDetail', ['book' => $book]);
	}
}
