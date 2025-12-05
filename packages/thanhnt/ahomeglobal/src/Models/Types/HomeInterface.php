<?php

namespace Thanhnt\Ahomeglobal\Models\Types;

interface HomeInterface{
	const TABLE_NAME = 'homes';

	const ID = 'id';
	const NAME = 'name';
	const DESCRIPTION = 'description'; 
	const DISTRICT = 'district';

	const HIDDEN_FIELDS = ['created_at', 'updated_at'];
}