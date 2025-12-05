<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface RoomInterface{
	const TABLE_NAME = 'rooms';

	const ID = 'id';
	const TITLE = 'title';
	const DESCRIPTION = 'description';
	/**
	 * one, two, three, four, five, six
	 */
	const TYPE = 'type';
	const TYPE_VALUE = ['one', 'two', 'three', 'four', 'five', 'six', 'all'];
	
	const HOME_ID = 'home_id';
}