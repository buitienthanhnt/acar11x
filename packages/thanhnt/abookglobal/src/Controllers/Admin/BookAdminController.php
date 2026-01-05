<?php

namespace Thanhnt\Abookglobal\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thanhnt\Abookglobal\Models\Book;
use Thanhnt\Abookglobal\Models\BookCate;
use Thanhnt\Abookglobal\Models\Types\BookCateInterface;
use Thanhnt\Abookglobal\Models\Types\BookInterface;
use Thanhnt\Abookglobal\Repository\BookCateRepository;
use Thanhnt\Abookglobal\Repository\BookRepository;

final class BookAdminController extends Controller
{
	public function __construct(
		protected BookRepository $bookRepository,
		protected BookCateRepository $bookCateRepository,
	) {
		// throw new \Exception('Not implemented');
	}
	public function index()
	{
		$actions = [];

		return view('adminhtml.pages.abookglobal.books.index', [
			'attributes' => [
				BookInterface::ID,
				BookInterface::IMAGE_PATH,
				BookInterface::NAME,
				BookInterface::PRICE,
				BookInterface::DESCRIPTION,
			],
			'lists' => Book::paginate(12),
			'actions' => $actions,
		]);
	}

	public function createBook()
	{
		$listAttributes = BookInterface::FORM_FIELDS;
		return view('adminhtml.pages.abookglobal.books.create', [
			'listAttributes' => $listAttributes,
			'optionAttribute' => array_map(function ($field) {
				return [
					...$field,
					// 'key' => "attrs[" . $field['key'] . "]",
				];
			}, BookInterface::CUSTOM_ATTRS)
		]);
	}

	public function RegisterBook(Request $request)
	{
		$this->bookRepository->register($request->all());
		return redirect()->to('adminhtml/abook/book-list')->with('message', 'created new book!');
	}

	public function  listBookCates()
	{
		return view('adminhtml.pages.abookglobal.bookcate.index', [
			'attributes' => [
				BookCateInterface::ID,
				BookCateInterface::NAME,
				BookCateInterface::IMAGE_PATH,
				BookCateInterface::DESCRIPTION,
			],
			'lists' => BookCate::paginate(12),
		]);
	}

	public function createBookCate()
	{
		$listAttributes = BookCateInterface::FORM_FIELDS;
		return view('adminhtml.pages.abookglobal.bookcate.create', [
			'listAttributes' => $listAttributes,
		]);
	}

	public function registerBookCate(Request $request)
	{
		$this->bookCateRepository->register($request->all());
		return redirect()->to('adminhtml/abook/book-cate')->with('message', 'created new book!');
	}
}
