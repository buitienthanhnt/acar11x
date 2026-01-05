<?php

namespace Thanhnt\Abookglobal\Models\Types;

use App\Models\Types\FormInterface;
use Thanhnt\Abookglobal\Models\BookCate;

interface BookCateInterface
{
	const ID = 'id';
	const TABLE_NAME = 'book_cates';

	const NAME = 'name';
	const DESCRIPTION = 'description';
	const IMAGE_PATH = 'image_path';
	const PARENT = 'parent';

	const DEFAULT_SELECT = [self::ID, self::NAME, self::DESCRIPTION, self::PARENT, self::IMAGE_PATH,];

	/**
	 * define main form fields for Home model
	 */
	const FORM_FIELDS = [
		self::NAME => ['key' => self::NAME, 'type' => FormInterface::TYPE_TEXT, 'label' => 'Tên danh mục', 'required' => true,],
		self::IMAGE_PATH => ['key' => self::IMAGE_PATH, 'type' => FormInterface::TYPE_IMAGE_CHOOSE, 'label' => 'Ảnh đại diện',],
		self::DESCRIPTION => ['key' => self::DESCRIPTION, 'type' => FormInterface::TYPE_TEXTAREA, 'label' => 'mô tả chung',],
		// self::PARENT => ['key' => self::PARENT, 'type' => FormInterface::TYPE_SELECT, 'model' => BookCate::class, 'label' => 'the loai cha',],
	];

	const FILLED_FILEDS = [self::NAME, self::DESCRIPTION, self::IMAGE_PATH, self::PARENT,];
}
