<?php

namespace Thanhnt\Acarglobal\Models\Types;

interface AcarConfigInterface
{

	const TABLE_NAME  = 'acar_configs';
	const ID = 'id';
	const KEY = 'key';
	const VALUE = 'value';

	const USE_TIMESTAMP = false;

	const FILLED_FIELDS = [self::ID, self::KEY, self::VALUE];
}
