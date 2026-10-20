<?php

namespace Thanhnt\Amuaglobal\Models\Types;

interface CategoryInterface
{
	const TABLE_NAME = 'amua_categories';

	const ID = 'id';
	const NAME = 'name';
	const ALIAS = 'alias';
	const DESCRIPTION = 'description';
	const PARENT_ID = 'parent_id';
	const IMAGE_PATH = 'image_path';
	const ACTIVE = 'active';

	const FORM_FIELDS = [];
	const FILLED_FIELDS = [self::ACTIVE, self::NAME, self::ALIAS, self::PARENT_ID, self::DESCRIPTION, self::IMAGE_PATH];
	const HIDDEN_FIELDS = [];
}
