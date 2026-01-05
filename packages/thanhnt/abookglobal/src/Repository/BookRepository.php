<?php

namespace Thanhnt\Abookglobal\Repository;

use Thanhnt\Abookglobal\Models\Book;
use Thanhnt\Abookglobal\Models\Types\BookInterface;
use Thanhnt\Ahomeglobal\Helper\ModelHelper;
use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\GalleryInterface;

final class BookRepository
{
	public function __construct(
		protected Book $book,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}
	public function register(array $data = [])
	{
		// dd($data);

		$book = $this->book->create($this->modelHelper->massDataAttribute(BookInterface::FILLED_FILEDS, $data));

		/**
		 * save gallery
		 */
		$this->saveGallery($book, explode(',', $data[BookInterface::GALLERY]) ?? []);


		// page_count, detail
		$this->saveAttr($book, $data);

		/**
		 * sync book cate
		 */
		$this->saveBookCate($book, $data[BookInterface::BOOK_CATE] ?? []);
	}

	public function saveBookCate($book, $bookCate)
	{
		$book->bookCate()->sync($bookCate);
	}


	/**
	 * @param Book $book
	 * @param string[] $data
	 * @return void
	 */
	public function saveGallery(Book $book, array $data)
	{
		/**
		 * delete old gallery
		 */
		$book->gallery()->delete();
		/**
		 * create new gallery
		 */
		$book->gallery()->createMany(
			array_map(function ($item) {
				return [
					GalleryInterface::TYPE => 'book',
					GalleryInterface::PATH => $item,
				];
			}, $data)
		);
	}

	/**
	 * insert multil record home attribute
	 * @param Book $book
	 * @param array $data
	 * @return bool
	 */
	protected function saveAttr(Book $book, array|null $data)
	{
		$this->deleteAttrs($book);
		/**
		 * custom define for 2 attrs 
		 */
		$customData = [];

		if (!empty($data[BookInterface::DETAIL])) {
			$customData[BookInterface::DETAIL] =  $data[BookInterface::DETAIL];
		}

		if (!empty($data[BookInterface::PAGE_COUNT])) {
			$customData[BookInterface::PAGE_COUNT] =  $data[BookInterface::PAGE_COUNT];
		}

		$listAttr = [];
		/**
		 * format request home attribute
		 */
		foreach ($customData as $key => $value) {
			$listAttr[] = [
				AttrInterface::SOURCE_ID => $book->id,
				AttrInterface::TYPE => 'book',
				AttrInterface::KEY => $key,
				AttrInterface::VALUE => $value,
			];
		}

		/**
		 * insert for multi record
		 * @return bool
		 */
		return $newAttr = Attr::insert($listAttr);
	}

	/**
	 * @param Book $book
	 * @return void
	 */
	public function deleteAttrs($book)
	{
		/**
		 * delete old attributes
		 */
		$book->attr()->forceDelete();
	}
}
