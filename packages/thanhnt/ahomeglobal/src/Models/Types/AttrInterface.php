<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface AttrInterface{
	// table name
	const TABLE_NAME = 'ahome_attr';

	/**
	 * main attribute
	 */
	const ID = 'id';
	const SOURCE_ID = 'source_id';
	const TYPE = 'type';
	const KEY = 'key';
	const VALUE = 'value';

	/**
	 * fillable fields for model(mass assignment)
	 */
	const FILLED_FIELDS = [self::SOURCE_ID, self::TYPE, self::KEY, self::VALUE];

	/**
	 * define list hidden fields
	 */
	const HIDDEN_FIELDS = [];
}