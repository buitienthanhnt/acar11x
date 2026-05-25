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
	const IMAGE_PATH = 'image_path';

	const FILLED_FILEDS = [
		self::NAME,
		self::ALIAS,
		self::SKU,
		self::PRICE,
		self::BASE_PRICE,
		self::QTY,
		self::DESCRIPTION,
		self::IMAGE_PATH,
	];
}
