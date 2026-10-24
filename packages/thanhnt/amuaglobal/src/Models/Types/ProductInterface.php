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
	/**
	 * cần thống nhất các ảnh đại diện là: image
	 */
	const IMAGE = 'image';

	const FILLED_FILEDS = [
		self::NAME,
		self::ALIAS,
		self::SKU,
		self::PRICE,
		self::BASE_PRICE,
		self::QTY,
		self::DESCRIPTION,
		self::IMAGE,
	];

	const FORM_fIELDS = [];
}
