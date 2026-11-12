<?php

namespace Thanhnt\Amuaglobal\Models\Types;

interface ProductInterface
{
	const TABLE_NAME = 'amua_products';

	const ID = 'id';
	const NAME = 'name';
	const ALIAS = 'alias';
	const SKU = 'sku';
	const PRICE = 'price';
	const BASE_PRICE = 'base_price';
	const QTY = 'qty';
	const DESCRIPTION = 'description';
	const CATEGORY_IDS = 'category_ids';
	const UNIT = 'unit';

	const R_CATEGORIES = 'categories';
	const R_GALLERIES = 'galleries';

	/**
	 * cần thống nhất các ảnh đại diện là: image
	 */
	const IMAGE = 'image';

	/**
	 * define massing attribute
	 */
	const FILLED_FILEDS = [
		self::NAME,
		self::ALIAS,
		self::SKU,
		self::PRICE,
		self::BASE_PRICE,
		self::QTY,
		self::DESCRIPTION,
		self::IMAGE,
		self::UNIT,
	];

	const FORM_fIELDS = [
		[
			'key' => self::NAME,
			'type' => FormInterface::TYPE_TEXT,
			'placeholder' => 'Nhập tên sản phẩm',
			'required' => true,
			'label' => "Tên sản phẩm",
		],
		[
			'key' => self::ALIAS,
			'type' => FormInterface::TYPE_TEXT,
			'placeholder' => 'Nhập alias sản phẩm',
			'label' => 'Đường dẫn thay thế',
		],
		[
			'key' => self::SKU,
			'type' => FormInterface::TYPE_TEXT,
			'placeholder' => 'Nhập mã sản phẩm',
			'label' => 'Mã sản phẩm',
		],
		[
			'key' => self::QTY,
			'type' => FormInterface::TYPE_TEXT,
			'placeholder' => 'Nhập số lượng sản phẩm',
			'label' => 'Số lượng',
		],
		[
			'key' => self::BASE_PRICE,
			'type' => FormInterface::TYPE_TEXT,
			'placeholder' => 'Nhập giá gốc sản phẩm',
			'required' => true,
			'label' => 'Giá nhập'
		],
		[
			'key' => self::PRICE,
			'type' => FormInterface::TYPE_TEXT,
			'placeholder' => 'Nhập giá sản phẩm',
			'required' => true,
			'label' => 'Giá bán',
		],
		[
			'key' => self::DESCRIPTION,
			'type' => FormInterface::TYPE_TEXTAREA,
			'placeholder' => 'Nhập mô tả sản phẩm',
			'label' => 'Mô tả',
		],
		[
			'key' => self::CATEGORY_IDS,
			'type' => FormInterface::TYPE_SELECT,
			'label' => 'Danh mục sản phẩm',
			'options_source' => \Thanhnt\Amuaglobal\Models\DataSource\CategorySource::class,
			'placeholder' => 'Select parent category',
		],
		[
			'key' => self::IMAGE,
			'type' => FormInterface::TYPE_IMAGE_CHOOSE,
			'placeholder' => 'Nhập hình ảnh sản phẩm',
			'label' => 'Ảnh đại diện',
		],
		[
			'key' => 'galleries',
			'type' => FormInterface::TYPE_IMAGE_SELECT,
			'placeholder' => 'Nhập hình ảnh sản phẩm',
			'label' => 'Ảnh chi tiết',
		],
		[
			'key' => self::UNIT,
			'type' => FormInterface::TYPE_TEXT,
			'placeholder' => 'Nhập đơn vị tính',
			'label' => 'Đơn vị tính',
			'suggest' => ['Lít', 'Cái', 'Chiếc', 'Chai', 'Can', 'Công', 'Bộ', 'Hộp', 'Lần', 'Lượt', 'Ngày', 'Giờ', 'Phút', 'Km', 'Mile']
		],
	];
}
