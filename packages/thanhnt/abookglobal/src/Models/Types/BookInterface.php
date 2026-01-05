<?php

namespace Thanhnt\Abookglobal\Models\Types;

use Thanhnt\Abookglobal\Models\BookCate;
use Thanhnt\Abookglobal\Models\Publisher;
use Thanhnt\Ahomeglobal\Models\Types\FormInterface;

interface BookInterface
{
	const TABLE_NAME = 'books';

	const ID = 'id';
	const NAME = 'name';
	const DESCRIPTION = 'description';
	const PRICE = 'price';
	const IMAGE_PATH = 'image_path';
	const QTY = 'qty';
	const RATE = 'rate';
	const PUBLISHER = 'publisher';

	/**
	 * relations to bookCate
	 */
	const BOOK_CATE = 'bookCate';

	/**
	 * custom fields
	 */
	const GALLERY = 'gallery';
	const PAGE_COUNT = 'page_count';
	const DETAIL = 'detail';

	/**
	 * default select for model
	 */
	const DEFAULT_SELECT = [self::ID, self::NAME, self::DESCRIPTION, self::PRICE, self::IMAGE_PATH, self::QTY, self::RATE, self::PUBLISHER];

	/**
	 * define main form fields for Home model
	 */
	const FORM_FIELDS = [
		self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'Ảnh đại diện',],
		self::NAME => ['key' => self::NAME, 'type' => FormInterface::TYPE_TEXT, 'label' => 'Tên sách', 'required' => true,],
		self::DESCRIPTION => ['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả chung',],
		self::PRICE => ['key' => self::PRICE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'giá thuê theo ngày(nghìn vnd)',],
		self::QTY => ['key' => self::QTY, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'Số lượng',],
		self::RATE => ['key' => self::RATE, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'đánh giá',],
		self::PUBLISHER => ['key' => self::PUBLISHER, 'type' => FormInterface::TYPE_SELECT, 'model' => Publisher::class, 'required' => true, 'label' => 'Nhà xuất bản',],
	];

	/**
	 * define custom attributes for Home model
	 * khai báo thuộc tính biểu mẫu tùy chỉnh để tạo form.
	 */
	const CUSTOM_ATTRS = [
		self::BOOK_CATE => ['key' => self::BOOK_CATE, 'type' => FormInterface::TYPE_MULTISELECT, 'model' => BookCate::class, 'label' => 'Danh mục', 'fn' => 'bookCateOptions',],
		self::GALLERY => ['key' => self::GALLERY, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'ảnh chi tiết',],
		self::PAGE_COUNT => ['key' => self::PAGE_COUNT, 'type' => FormInterface::TYPE_NUMBER, 'label' => 'số trang', 'placeholder' => 'số trang',],
		self::DETAIL => ['key' => self::DETAIL, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả chi tiết',],
	];

	/**
	 * define default fields for mass assign
	 */
	const FILLED_FILEDS = [self::NAME, self::DESCRIPTION, self::PRICE, self::IMAGE_PATH, self::QTY, self::RATE, self::PUBLISHER];
}
