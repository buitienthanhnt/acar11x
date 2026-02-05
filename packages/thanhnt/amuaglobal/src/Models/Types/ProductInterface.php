<?php

namespace Thanhnt\Amuaglobal\Models\Types;

interface ProductInterface
{
	const TABLE_NAME = 'amua_products';

	const ID = 'id';
	const NAME = 'name';
	const ALIAS = 'alias';
	const DESCRIPTION = 'description';
	const IMAGE_PATH = 'image_path';

	const FILLED_FILEDS = [self::NAME, self::ALIAS, self::DESCRIPTION, self::IMAGE_PATH,];
}
