<?php

namespace Thanhnt\Amuaglobal\Models\Types;

interface GalleryInterface
{
	const TABLE_NAME = 'amua_galleries';
	
	const SOURCE_ID = 'source_id';
	const TYPE = 'type';
	const PATH = 'path';

	/**
	 * type value in list
	 */
	const TYPE_HOME = 'home';
	const TYPE_ROOM = 'room';
	const TYPE_BOOK = 'book';
	const TYPE_PRODUCT = 'product';

	/**
	 * define for mass assignment
	 */
	const FILLED_FILEDS = [
		self::SOURCE_ID,
		self::TYPE,
		self::PATH,
	];

	const HIDDEN_FIELDS = [self::TYPE, self::SOURCE_ID];
}
